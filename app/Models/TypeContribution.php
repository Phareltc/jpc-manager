<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeContribution extends Model
{
    use HasFactory;

    protected $table = 'type_contributions';
    
    protected $fillable = [
        'libelle',
        'code',
        'categorie',
        'montant_defaut',
        'est_actif',
    ];

    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }
}