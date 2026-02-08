<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'action',
        'description',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}
