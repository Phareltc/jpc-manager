<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    protected $fillable = [
        'membre_id',
        'type_contribution_id',
        'montant',
        'date_paiement',
        'annee_concernee',
        'mois_concerne',
        'mode_paiement',
        'observation',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'montant' => 'float',
        'annee_concernee' => 'integer',
        'mois_concerne' => 'integer',
    ];

    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }

    public function typeContribution()
    {
        // Eloquent trouvera automatiquement 'type_contribution_id'
        return $this->belongsTo(TypeContribution::class);
    }
}