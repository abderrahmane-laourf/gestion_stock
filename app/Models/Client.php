<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


    use Illuminate\Database\Eloquent\Factories\HasFactory;

    class Client extends Model
    {
        use HasFactory;

        // fillable est une propriete qui permet de specifier les champs qui peuvent etre assignes a un model 
         protected $fillable = [
            'nom' ,
            'prenom' ,
            'telephone' ,
         'adresse' 
      ];

      // Relation avec Commande
      public function commandes()
      {
          return $this->hasMany(Commande::class);
      }
    }
