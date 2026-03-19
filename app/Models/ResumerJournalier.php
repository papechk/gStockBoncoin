<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumerJournalier extends Model
{
    /** @use HasFactory<\Database\Factories\ResumerJournalierFactory> */
    use HasFactory;

    protected $table = 'resumer_journaliers';

    protected $fillable = [
        'boutique_id',
        'date',
        'nombre_ventes',
        'total_ventes',
        'benefice_net',
        'depenses'
    ];

    protected $casts = [
        'date' => 'date',
        'total_ventes' => 'decimal:2',
        'benefice_net' => 'decimal:2',
        'depenses' => 'decimal:2'
    ];

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }
}
