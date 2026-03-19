<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VenteDetail;

class Produit extends Model
{
    /** @use HasFactory<\Database\Factories\ProduitFactory> */
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'prix_achat',
        'prix_vente',
        'quantite_stock',
        'categorie_id',
        'image',
        'code_barre',
        'seuil_alerte'
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function mvtStocks()
    {
        return $this->hasMany(MvtStock::class);
    }

    public function venteDetails()
    {
        return $this->hasMany(VenteDetail::class);
    }
}
