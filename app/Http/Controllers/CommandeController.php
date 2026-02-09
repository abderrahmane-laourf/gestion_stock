<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Client;
use App\Models\Produit;

class CommandeController extends Controller
{
    /**
     * Afficher la liste des commandes avec filtres
     * Hna kanjebu ga3 les commandes, w kanfiltriw hasb le client, date de début/fin
     * Les résultats sont triés par date décroissante (les plus récentes en premier)
     */
    public function index(Request $request)
    {
        $query = Commande::with(['client', 'produits']);

        // Filtres
        if ($request->client_id) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->date_debut) {
            $query->whereDate('date_commande', '>=', $request->date_debut);
        }
        if ($request->date_fin) {
            $query->whereDate('date_commande', '<=', $request->date_fin);
        }

        $commandes = $query->orderBy('date_commande', 'desc')->paginate(10);
        $clients = Client::all();

        return view('commande.index', compact('commandes', 'clients'));
    }

    /**
     * Afficher le formulaire de création d'une commande
     * Hna kanjebu la liste dyal les clients w les produits
     * w kanaffichiwha f la page de création
     */
    public function create()
    {
        $clients = Client::all();
        $produits = Produit::all();
        return view('commande.create', compact('clients', 'produits'));
    }

    /**
     * Enregistrer une nouvelle commande (brouillon)
     * Hna kanvalidiw les données (client, date, adresse, produits)
     * Kancrééw la commande b statut 'brouillon' w montant 0
     * Ila kaynin des produits, kanattachiwhom w kanhasbu le total
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

        $commande->log('created', 'Commande créée');

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
     * Afficher les détails d'une commande avec ses produits
     * Hna kanjebu la commande m3a le client, les produits, w l'historique
     * Kan affichiw aussi la liste de tous les produits/clients bach nmodifiw
     */
    public function show($id)
    {
        $commande = Commande::with(['client', 'produits', 'history'])->findOrFail($id);
        $produits = Produit::all();
        $clients = Client::all();

        return view('commande.show', compact('commande', 'produits', 'clients'));
    }

    /**
     * Mettre à jour les informations générales de la commande
     * Hna kan3edlu le client, la date, w l'adresse de livraison
     * Kanvalidiw les données, kanfaireuw update, w kanloggiw f l'historique
     */
    public function update(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date_commande' => 'required|date',
            'adresse_livraison' => 'required|string',
        ]);

        $commande->update($validated);
        $commande->log('updated', 'Informations générales modifiées');

        return back()->with('success', 'Commande mise à jour.');
    }

    /**
     * Supprimer une commande
     * Hna kanmshu la commande men la base de données
     * Kanreturniw l la liste m3a un message de succès
     */
    public function destroy($id)
    {
        $commande = Commande::findOrFail($id);
        $commande->delete();
        return redirect()->route('commandes.index')->with('success', 'Commande supprimée.');
    }

    // --- Gestion des produits dans la commande ---

    /**
     * Ajouter un produit à la commande
     * Hna kancheckkiw wach le produit deja kayn f la commande
     * Ila makaynch, kanattachiwiw m3a la quantité w le prix actuel
     * Kanhesbu le total de nouveau w kanloggiw l'action
     */
    public function addProduit(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);

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

    /**
     * Retirer un produit de la commande
     * Hna kanfslu le produit men la commande (detach)
     * Kan3awdu nhesbu le montant total w kanloggiw
     */
    public function removeProduit($commandeId, $produitId)
    {
        $commande = Commande::findOrFail($commandeId);

        $commande->produits()->detach($produitId);
        $this->recalculate($commandeId);
        $commande->log('product_removed', "Suppression d'un produit");

        return back()->with('success', 'Produit retiré.');
    }

    // --- Fonctionnalités supplémentaires ---

    /**
     * Recalculer le montant total de la commande
     * Hna kanjm3u (quantité × prix) dyal kol produit
     * Ila t3aytna liha men le route directement, kanloggiw w kanbiinuw message
     */
    public function recalculate($id)
    {
        $commande = Commande::findOrFail($id);

        $total = $commande->calculateTotal();
        $commande->update(['montant_total' => $total]);
        
        // If called directly via route, log it
        if (request()->routeIs('commandes.recalculate')) {
            $commande->log('recalculated', "Total recalculé: $total");
            return back()->with('success', 'Total recalculé.');
        }
    }

    /**
     * Recherche avancée des commandes
     * Kanqalbu b l'ID wla smiya dyal le client, w kanfiltriw b le montant minimum
     */
    public function search(Request $request)
    {
        // Njibuw les commandes m3a les clients dyalhom
        $query = Commande::with('client');

        // Ila l'utilisateur dkhal chi haja f champ de recherche
        if ($request->filled('recherche')) {
            $recherche = $request->recherche;

            // Kanqalbuw 3la l'ID dyal la commande
            $query->where('id', $recherche)
                  // Wla kanqalbuw 3la smiya dyal le client
                  ->orWhereHas('client', function($c) use ($recherche) {
                      $c->where('nom', 'like', "%$recherche%");
                  });
        }
        
        // Ila l'utilisateur dkhal montant minimum, kanfiltriw bih
        if ($request->filled('min_amount')) {
            $query->where('montant_total', '>=', $request->min_amount);
        }

        $commandes = $query->orderBy('date_commande', 'desc')->paginate(10);
        $clients = Client::all();

        return view('commande.index', compact('commandes', 'clients'));
    }

    /**
     * Notifier le client (simulation)
     * Hna kansimuliw l'envoi d'une notification l le client
     * Makatb3at bessa7, ghir kanloggiw f l'historique
     */
    public function notifyClient($id)
    {
        $commande = Commande::with('client')->findOrFail($id);
        // Simulation
        $commande->log('notification', "Notification envoyée au client {$commande->client->nom}");
        
        return back()->with('success', "Notification envoyée à {$commande->client->nom} (Simulation).");
    }

    /**
     * Afficher l'historique d'une commande
     * Hna kanbiynu ga3 les actions lli wq3u 3la had la commande
     * (Création, modification, ajout/suppression de produit...)
     */
    public function history($id)
    {
        $commande = Commande::with('history')->findOrFail($id);
        return view('commande.history', compact('commande'));
    }

    /**
     * Afficher la page d'impression de la commande
     * Hna kanbiyynu la commande f format dial impression (Bon de commande)
     * Katft7 f tab jdid bach user ytab3ha
     */
    public function print($id)
    {
        $commande = Commande::with(['client', 'produits'])->findOrFail($id);
        return view('commande.print', compact('commande'));
    }

    /**
     * Exporter la commande en PDF
     * Hna kan3amlu redirect l la page dial print b le mode PDF
     * Hiya nfss la page dyal print ghir b paramètre zayda
     */
    public function exportPdf($id)
    {
        return redirect()->route('commandes.print', ['id' => $id, 'mode' => 'pdf']);
    }


}
