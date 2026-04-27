<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scolarite;

class Membre extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'sexe',
        'date_naissance',
        'date_bapteme',
        'telephone',
        'niveau_etude',
        'statut_professionnel',
        'role_spirituel',
        'structure_mouvement',
        'statut_matrimonial'
    ];

    // Dans app/Models/Membre.php
    public function scolarites()
    {
        return $this->hasMany(Scolarite::class);
    }
}
