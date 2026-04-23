<?php

namespace Database\Seeders;

use App\Models\Membre;
use Illuminate\Database\Seeder;

class MembreSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nom' => 'Mba', 'prenom' => 'Jean', 'sexe' => 'M', 'niveau_etude' => 'Université'],
            ['nom' => 'Nze', 'prenom' => 'Marie', 'sexe' => 'F', 'niveau_etude' => 'Secondaire'],
            ['nom' => 'Obame', 'prenom' => 'Karl', 'sexe' => 'M', 'niveau_etude' => 'Primaire'],
            ['nom' => 'Biveghe', 'prenom' => 'Alice', 'sexe' => 'F', 'niveau_etude' => 'Université'],
            ['nom' => 'Zue', 'prenom' => 'Paul', 'sexe' => 'M', 'niveau_etude' => 'Secondaire'],
        ];

        foreach ($data as $membre) {
            Membre::create(array_merge($membre, [
                'date_naissance' => '2000-01-01',
                'telephone' => '077000000',
                'statut_professionnel' => 'Étudiant',
                'role_spirituel' => 'Membre',
                'statut_matrimonial' => 'Célibataire'
            ]));
        }
    }
}