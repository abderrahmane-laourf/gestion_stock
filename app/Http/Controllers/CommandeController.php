<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Client;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    /**
     * Display a listing of commandes with filters
     */
    public function index(Request $request)
    {
        $query = Commande::with(['client', 'produits']);

        // Filtres
        if ($request->client_id) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->statut) {
            $query->where('statut', $request->statut);
        }
        if ($request->date_debut) {
            $query->whereDate('date_commande', '>=', $request->date_debut);
        }
        if ($request->date_fin) {
            $query->whereDate('date_commande', '<=', $request->date_fin);
        }

        $commandes = $query->orderBy('date_commande', 'desc')->get();
        $clients = Client::all();

        return view('commande.index', compact('commandes', 'clients'));
    }

    /**
     * Store a new commande (brouillon)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date_commande' => 'required|date',
            'adresse_livraison' => 'required|string',
            'produits' => 'nullable|array',
            'produits.*.produit_id' => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|integer|min:1',
            'produits.*.prix_unitaire' => 'required|numeric|min:0',
        ]);

        // Create commande
        $commande = Commande::create([
            'client_id' => $validated['client_id'],
            'date_commande' => $validated['date_commande'],
            'adresse_livraison' => $validated['adresse_livraison'],
            'statut' => 'brouillon',
            'montant_total' => 0,
        ]);

        $commande->log('created', 'Commande créée (brouillon)');

        // Attach products if provided
        if (isset($validated['produits'])) {
            foreach ($validated['produits'] as $produit) {
                $commande->produits()->attach($produit['produit_id'], [
                    'quantite' => $produit['quantite'],
                    'prix_unitaire' => $produit['prix_unitaire'],
                ]);
            }
            
            // Update total
            $commande->montant_total = $commande->calculateTotal();
            $commande->save();
        }

        return redirect()->route('commandes.index')
            ->with('success', 'Commande ajoutée avec succès.');
    }

    /**
     * Show commande details with products
     */
    public function show($id)
    {
        $commande = Commande::with(['client', 'produits', 'history'])->findOrFail($id);
        $produits = Produit::all();
        $clients = Client::all();

        return view('commande.show', compact('commande', 'produits', 'clients'));
    }

    /**
     * Update commande general info
     */
    public function update(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);
        
        if ($commande->statut !== 'brouillon') {
            return back()->with('error', 'Impossible de modifier une commande validée.');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date_commande' => 'required|date',
            'adresse_livraison' => 'required|string',
        ]);

        $commande->update($validated);
        $commande->log('updated', 'Informations générales modifiées');

        return back()->with('success', 'Commande mise à jour.');
    }

    public function destroy($id)
    {
        $commande = Commande::findOrFail($id);
        if ($commande->statut !== 'brouillon' && $commande->statut !== 'annulee') {
            return back()->with('error', 'Seules les commandes brouillon ou annulées peuvent être supprimées.');
        }
        $commande->delete();
        return redirect()->route('commandes.index')->with('success', 'Commande supprimée.');
    }

    // --- Product Management ---

    public function addProduit(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);
        if ($commande->statut !== 'brouillon') return back()->with('error', 'Commande verrouillée.');

        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
        ]);

        $produit = Produit::findOrFail($request->produit_id);
        
        // Check if exists
        if ($commande->produits()->where('produit_id', $produit->id)->exists()) {
            return back()->with('error', 'Produit déjà dans la commande.');
        }

        $commande->produits()->attach($produit->id, [
            'quantite' => $request->quantite,
            'prix_unitaire' => $produit->prix, // Use current product price
        ]);

        $this->recalculate($id);
        $commande->log('product_added', "Ajout du produit {$produit->nom} (x{$request->quantite})");

        return back()->with('success', 'Produit ajouté.');
    }

    public function removeProduit($commandeId, $produitId)
    {
        $commande = Commande::findOrFail($commandeId);
        if ($commande->statut !== 'brouillon') return back()->with('error', 'Commande verrouillée.');

        $commande->produits()->detach($produitId);
        $this->recalculate($commandeId);
        $commande->log('product_removed', "Suppression d'un produit");

        return back()->with('success', 'Produit retiré.');
    }

    // --- New Features ---

    public function recalculate($id)
    {
        $commande = Commande::findOrFail($id);
        if ($commande->statut !== 'brouillon') return back()->with('error', 'Commande verrouillée.');

        $total = $commande->calculateTotal();
        $commande->update(['montant_total' => $total]);
        
        // If called directly via route, log it
        if (request()->routeIs('commandes.recalculate')) {
            $commande->log('recalculated', "Total recalculé: $total");
            return back()->with('success', 'Total recalculé.');
        }
    }

    public function validateOrder($id)
    {
        $commande = Commande::with('produits')->findOrFail($id);
        
        if ($commande->statut !== 'brouillon') {
            return back()->with('error', 'Cette commande n\'est pas un brouillon.');
        }

        // Check stock
        foreach ($commande->produits as $produit) {
            if ($produit->stock < $produit->pivot->quantite) {
                return back()->with('error', "Stock insuffisant pour {$produit->nom} (Dispo: {$produit->stock})");
            }
        }

        DB::transaction(function () use ($commande) {
            // Deduct stock
            foreach ($commande->produits as $produit) {
                $produit->decrement('stock', $produit->pivot->quantite);
            }
            
            $commande->update(['statut' => 'confirmee']);
            $commande->log('validated', 'Commande validée et stock mis à jour');
        });

        return back()->with('success', 'Commande validée avec succès.');
    }

    public function cancel($id)
    {
        $commande = Commande::with('produits')->findOrFail($id);
        
        if (in_array($commande->statut, ['annulee', 'livree', 'cloturee'])) {
            return back()->with('error', 'Impossible d\'annuler cette commande.');
        }

        DB::transaction(function () use ($commande) {
            // Restore stock if it was validated
            if ($commande->statut !== 'brouillon') {
                foreach ($commande->produits as $produit) {
                    $produit->increment('stock', $produit->pivot->quantite);
                }
            }

            $commande->update(['statut' => 'annulee']);
            $commande->log('cancelled', 'Commande annulée, stock restauré si nécessaire');
        });

        return back()->with('success', 'Commande annulée.');
    }

    public function ship($id)
    {
        $commande = Commande::findOrFail($id);
        
        if ($commande->statut !== 'confirmee') {
            return back()->with('error', 'Seule une commande confirmée peut être expédiée.');
        }

        $commande->update(['statut' => 'en_cours']);
        $commande->log('shipped', 'Commande expédiée (en cours)');

        return back()->with('success', 'Commande marquée comme en cours de livraison.');
    }

    public function deliver($id)
    {
        $commande = Commande::findOrFail($id);
        
        if (!in_array($commande->statut, ['confirmee', 'en_cours'])) {
            return back()->with('error', 'Seule une commande confirmée ou en cours peut être livrée.');
        }

        $commande->update(['statut' => 'livree']);
        $commande->log('delivered', 'Commande marquée comme livrée');

        return back()->with('success', 'Commande livrée.');
    }

    public function close($id)
    {
        $commande = Commande::findOrFail($id);
        
        if ($commande->statut !== 'livree') {
            return back()->with('error', 'Il faut livrer la commande avant de la clôturer.');
        }

        $commande->update(['statut' => 'cloturee']);
        $commande->log('closed', 'Commande clôturée définitivement');

        return back()->with('success', 'Commande clôturée.');
    }

    public function archive($id)
    {
        $commande = Commande::findOrFail($id);
        
        if (!in_array($commande->statut, ['cloturee', 'annulee'])) {
            return back()->with('error', 'Seules les commandes clôturées ou annulées peuvent être archivées.');
        }

        $commande->update([
            'statut' => 'archivee',
            'archived_at' => now()
        ]);
        $commande->log('archived', 'Commande archivée');

        return back()->with('success', 'Commande archivée.');
    }

    public function restore($id)
    {
        $commande = Commande::findOrFail($id);
        
        if ($commande->statut !== 'archivee') {
            return back()->with('error', 'Commande non archivée.');
        }

        // Restore to 'cloturee' if it was valid, or 'annulee'. 
        // Simple logic: Restore to 'brouillon' allows full edit, but 'cloturee' is safer history.
        // Let's check history to see previous status?
        // Simple method: Restore to 'brouillon' so it can be managed again, or 'cloturee' (read-only).
        // User asked "Restaure une commande archivé".
        // Let's set it to 'cloturee' by default as safe state.
        $commande->update(['statut' => 'cloturee', 'archived_at' => null]);
        $commande->log('restored', 'Commande restaurée depuis les archives');

        return back()->with('success', 'Commande restaurée.');
    }

    public function search(Request $request)
    {
        $query = Commande::with('client');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('id', $q)
                    ->orWhereHas('client', function($c) use ($q) {
                        $c->where('nom', 'like', "%$q%");
                    });
            });
        }
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->filled('min_amount')) {
            $query->where('montant_total', '>=', $request->min_amount);
        }

        $commandes = $query->orderBy('date_commande', 'desc')->get();
        $clients = Client::all();

        // Return same index view but with results
        return view('commande.index', compact('commandes', 'clients'));
    }

    public function notifyClient($id)
    {
        $commande = Commande::with('client')->findOrFail($id);
        // Simulation
        $commande->log('notification', "Notification envoyée au client {$commande->client->nom}");
        
        return back()->with('success', "Notification envoyée à {$commande->client->nom} (Simulation).");
    }

    public function history($id)
    {
        $commande = Commande::with('history')->findOrFail($id);
        return view('commande.history', compact('commande'));
    }

    public function print($id)
    {
        $commande = Commande::with(['client', 'produits'])->findOrFail($id);
        return view('commande.print', compact('commande'));
    }

    public function exportPdf($id)
    {
        // Simple logic: Redirect to print view with a query param that could trigger auto-print
        // Or strictly strictly: "Génère un PDF".
        // Without libraries, we can't generate a binary PDF.
        // We will return the print view with a hint to save as PDF.
        return redirect()->route('commandes.print', ['id' => $id, 'mode' => 'pdf']);
    }

    public function exportExcel($id)
    {
        $commande = Commande::with(['client', 'produits'])->findOrFail($id);
        
        $filename = "commande_{$id}.csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($commande) {
            $file = fopen('php://output', 'w');
            
            // Header info
            fputcsv($file, ['Commande ID', $commande->id]);
            fputcsv($file, ['Client', $commande->client->nom]);
            fputcsv($file, ['Date', $commande->date_commande->format('Y-m-d')]);
            fputcsv($file, ['Total', $commande->montant_total]);
            fputcsv($file, []);
            
            // Products
            fputcsv($file, ['Produit', 'Quantite', 'Prix Unitaire', 'Total Ligne']);
            foreach ($commande->produits as $produit) {
                fputcsv($file, [
                    $produit->nom, 
                    $produit->pivot->quantite, 
                    $produit->pivot->prix_unitaire,
                    $produit->pivot->quantite * $produit->pivot->prix_unitaire
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
