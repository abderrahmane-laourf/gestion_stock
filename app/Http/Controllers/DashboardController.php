<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Chiffre d'affaire (Orders that are confirmed, shipped, delivered, or closed)
        // We exclude 'brouillon', 'annulee', 'archivee' (unless archivee implies sold? Let's assume archivee is also sold)
        // Actually 'archivee' was 'cloturee' or 'annulee'. We should check the history or just assume if it's archived it might be valuable?
        // Let's stick to active successful statuses for "Revenue".
        $revenueStatuses = ['confirmee', 'en_cours', 'livree', 'cloturee'];
        
        $chiffreAffaire = Commande::whereIn('statut', $revenueStatuses)->sum('montant_total');

        // 2. Counts
        $nombreCommandes = Commande::count();
        $nombreProduits = Produit::count();
        $nombreClients = Client::count();
        $quantiteStock = Produit::sum('stock');

        // 3. Status Distribution for Chart
        $commandesParStatut = Commande::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut')
            ->toArray();
        
        // Ensure all statuses have a value for the chart even if 0
        $allStatuses = ['brouillon', 'confirmee', 'en_cours', 'livree', 'cloturee', 'annulee', 'archivee'];
        $chartData = [];
        foreach ($allStatuses as $status) {
            $chartData[$status] = $commandesParStatut[$status] ?? 0;
        }

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
            'chartData',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
