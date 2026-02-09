<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'client_id',
        'date_commande',
        'adresse_livraison',
        'montant_total',
    ];

    protected $casts = [
        'date_commande' => 'date',
        'montant_total' => 'decimal:2',
    ];

    // Relation avec Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relation many-to-many avec Produit
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'commande_produit')
            ->withPivot('quantite', 'prix_unitaire')
            ->withTimestamps();
    }

    // Historique
    public function history()
    {
        return $this->hasMany(CommandeHistory::class)->orderBy('created_at', 'desc');
    }

    // Helper pour ajouter un log
    public function log($action, $description = null)
    {
        $this->history()->create([
            'action' => $action,
            'description' => $description,
        ]);
    }

    // Calculer le total de la commande
    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->produits as $produit) {
            $total += $produit->pivot->quantite * $produit->pivot->prix_unitaire;
        }
        return $total;
    }
}
