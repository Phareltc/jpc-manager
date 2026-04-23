<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// Ces deux lignes sont cruciales pour que Spatie fonctionne :
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Nettoyer le cache des permissions (très important avec Spatie)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Création des rôles
        $admin = Role::create(['name' => 'admin']);
        $responsable = Role::create(['name' => 'responsable']);

        // Création de permissions
        Permission::create(['name' => 'supprimer membres']);
        Permission::create(['name' => 'editer statistiques']);

        // L'admin a tous les droits
        $admin->givePermissionTo(Permission::all());
    }
}