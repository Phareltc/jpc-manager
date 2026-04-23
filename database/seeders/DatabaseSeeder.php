<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. On lance d'abord le RoleSeeder pour créer les rôles en base
        $this->call(RoleSeeder::class);

        // 2. On crée ton compte administrateur
        $user = User::factory()->create([
            'name' => 'Admin JPC',
            'email' => 'vaneclaude872@gmail.com',
            'password' => bcrypt('passer1234'), // Change-le !
        ]);

        // 3. On lui donne le rôle 'admin'
        $user->assignRole('admin');

        // Optionnel : Créer un responsable de test
        $responsable = User::factory()->create([
            'name' => 'Responsable Jeunesse',
            'email' => 'responsable@eglise.com',
        ]);
        $responsable->assignRole('responsable');
    }
}
