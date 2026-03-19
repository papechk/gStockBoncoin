<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    /** @use HasFactory<\Database\Factories\VenteFactory> */
    use HasFactory;

    protected $fillable = [
        'boutique_id',
        'user_id',
        'numero_vente',
        'date_vente',
        'total',
        'montant_paye',
        'montant_rendu',
        'statut',
        'mode_paiement'
    ];

    protected $casts = [
        'date_vente' => 'datetime',
        'total' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'montant_rendu' => 'decimal:2'
    ];

    public function ligneVentes()
    {
        return $this->hasMany(LigneVente::class);
    }

    public function venteDetails()
    {
        return $this->hasMany(VenteDetail::class);
    }

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
