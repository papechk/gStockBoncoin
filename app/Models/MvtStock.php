<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MvtStock extends Model
{
    /** @use HasFactory<\Database\Factories\MvtStockFactory> */
    use HasFactory;

    protected $table = 'mvt_stocks';

    protected $fillable = [
        'produit_id',
        'boutique_id',
        'type',
        'quantite',
        'date_mouvement',
        'description',
        'user_id'
    ];

    protected $casts = [
        'date_mouvement' => 'datetime'
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
