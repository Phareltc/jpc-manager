<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
