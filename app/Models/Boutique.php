<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boutique extends Model
{
    /** @use HasFactory<\Database\Factories\BoutiqueFactory> */
    use HasFactory;

    protected $fillable = [
        'nom',
        'adresse',
        'telephone',
        'email'
    ];

    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function mvtStocks()
    {
        return $this->hasMany(MvtStock::class);
    }

    public function resumerJournaliers()
    {
        return $this->hasMany(ResumerJournalier::class);
    }
}
