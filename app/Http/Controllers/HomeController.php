<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    /**
     * Q1. Action index qui affiche le catalogue des produits
     */
    public function index()
    {
        $produits = Produit::all();
        return view('home.index', compact('produits'));
    }

    /**
     * Q3. Action add qui permet d’ajouter un produit au panier (Cookie)
     */
    public function add($id)
    {
        $produit = Produit::findOrFail($id);
        
        // Récupérer le panier existant ou initialiser un tableau vide
        $panier = json_decode(Cookie::get('panier', '[]'), true);
        
        
        // Vérifier si le produit est déjà dans le panier
        if (isset($panier[$id])) {
            $panier[$id]['quantite']++;
        } else {
            $panier[$id] = [
                'id' => $produit->id,
                'nom' => $produit->nom,
                'prix' => $produit->prix,
                'imageURL' => $produit->imageURL,
                'quantite' => 1
            ];
        }
        
        // Stocker le panier dans un cookie pour 30 jours (43200 minutes)
        // Note: queue() attaches the cookie to the next response automatically
        Cookie::queue('panier', json_encode($panier), 43200);
        
        return redirect()->back()->with('success', 'Produit ajouté au panier!');
    }

    /**
     * Q4. Action show_cart qui affiche le contenu du panier
     */
    public function show_cart()
    {
        $panier = json_decode(Cookie::get('panier', '[]'), true);
        
        // Convertir en collection ou tableau pour la vue si nécessaire, 
        // ou passer directement. Ici on passe le tableau.
        return view('home.cart', compact('panier'));
    }
    
    // Action helper pour vider le panier (utile pour le débug ou user exp)
    public function clear_cart()
    {
        Cookie::queue(Cookie::forget('panier'));
        return redirect()->route('home.cart')->with('success', 'Panier vidé.');
    }
    
    public function remove_from_cart($id)
    {
        $panier = json_decode(Cookie::get('panier', '[]'), true);
        if(isset($panier[$id])) {
            unset($panier[$id]);
            Cookie::queue('panier', json_encode($panier), 43200);
        }
        return redirect()->back()->with('success', 'Produit retiré.');
    }

    /**
     * Q7. Afficher le formulaire de commande
     */
    public function checkout()
    {
        $panier = json_decode(Cookie::get('panier', '[]'), true);
        
        if (empty($panier)) {
            return redirect()->route('home.index')->with('error', 'Votre panier est vide.');
        }

        return view('home.checkout', compact('panier'));
    }

    /**
     * Q8. Enregistrer la commande et le client
     */
    public function store_order(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'tele' => 'required|string|max:20',
            'ville' => 'required|string|max:255',
            'adresse' => 'required|string',
        ]);

        $panier = json_decode(Cookie::get('panier', '[]'), true);

        if (empty($panier)) {
            return redirect()->route('home.index')->with('error', 'Votre panier est vide.');
        }

        // Utilisation d'une transaction pour garantir l'intégrité des données
        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $panier) {
            // 1. Créer ou récupérer le client (ici on crée un nouveau à chaque fois selon la demande, 
            // mais on pourrait vérifier l'existence par téléphone/email)
            $client = \App\Models\Client::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'telephone' => $request->tele,
                'adresse' => $request->adresse . ' ' . $request->ville,
            ]);

            // Calculer le montant total
            $montant_total = array_reduce($panier, function($carry, $item) {
                return $carry + ($item['prix'] * $item['quantite']);
            }, 0);

            // 2. Créer la commande
            $commande = \App\Models\Commande::create([
                'client_id' => $client->id,
                'date_commande' => now(),
                'adresse_livraison' => $request->adresse, // L'adresse est stockée dans la commande
                'statut' => 'brouillon',
                'montant_total' => $montant_total
            ]);

            // 3. Enregistrer les détails (produits)
            foreach ($panier as $item) {
                $commande->produits()->attach($item['id'], [
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix']
                ]);
            }
        });

        // Vider le panier
        Cookie::queue(Cookie::forget('panier'));

        return redirect()->route('home.index')->with('success', 'Votre commande a été enregistrée avec succès !');
    }
}
