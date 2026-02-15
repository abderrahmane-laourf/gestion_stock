<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Commande;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       // Create Admin User
       User::create([
           'name' => 'Admin User',
           'username' => 'admin',
           'email' => 'admin@example.com',
           'password' => Hash::make('password'),
           'category' => 'admin',
           'image' => null,
       ]);

       Client::factory(10)->create();
       Categorie::factory(10)->create();
       Produit::factory(10)->create();
       
       // Create commandes with products
       Commande::factory(15)->create()->each(function ($commande) {
           // Attach 1-3 random products to each commande with smaller quantities
           $produits = Produit::inRandomOrder()->limit(rand(1, 3))->get();
           
           foreach ($produits as $produit) {
               $commande->produits()->attach($produit->id, [
                   'quantite' => rand(1, 3), // Reduced quantity to avoid overflow
                   'prix_unitaire' => $produit->prix,
               ]);
           }
           
           // Update montant_total
           $commande->montant_total = $commande->calculateTotal();
           $commande->save();
       });
    }
}
