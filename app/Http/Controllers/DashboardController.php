<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Client;

class DashboardController extends Controller
{
    public function index()
    {
        // Chiffre d'affaire (total de toutes les commandes)
        $chiffreAffaire = Commande::sum('montant_total');

        // 2. Counts
        $nombreCommandes = Commande::count();
        $nombreProduits = Produit::count();
        $nombreClients = Client::count();
        $quantiteStock = Produit::sum('stock');



        // 4. Recent Orders
        $recentOrders = Commande::with('client')->orderBy('created_at', 'desc')->take(5)->get();

        // 5. Low Stock Products
        $lowStockProducts = Produit::where('stock', '<', 10)->take(5)->get();

        return view('dashboard.index', compact(
            'chiffreAffaire',
            'nombreCommandes',
            'nombreProduits',
            'nombreClients',
            'quantiteStock',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
