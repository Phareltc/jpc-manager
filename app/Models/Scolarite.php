<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scolarite extends Model
{
    use HasFactory;

    protected $fillable = [
        'membre_id', 
        'annee_scolaire', 
        'etablissement', 
        'classe', 
        'serie', 
        'decision_finale'
    ];

    /**
     * Relation Inverse : Une scolarité appartient à un seul membre. 
     */
    public function membre()
    {
        return $this->hasMany(Membre::class);
    }

}