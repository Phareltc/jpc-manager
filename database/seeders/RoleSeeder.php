<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reinitialiser le cache des permissions (Indispensable)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Création ou récupération des permissions
        $permSupprimerMembres = Permission::firstOrCreate(['name' => 'supprimer membres', 'guard_name' => 'web']);
        $permEditerStats = Permission::firstOrCreate(['name' => 'editer statistiques', 'guard_name' => 'web']);

        // 3. Création ou récupération des rôles
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $responsable = Role::firstOrCreate(['name' => 'responsable', 'guard_name' => 'web']);

        // 4. Synchronisation des permissions
        // syncPermissions s'assure de lier toutes les permissions sans faire de doublons
        $admin->syncPermissions(Permission::all());
        $responsable->syncPermissions([$permEditerStats]);
    }
}