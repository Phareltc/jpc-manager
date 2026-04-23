<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->char('sexe', 1); // M ou F
            $table->date('date_naissance');
            $table->date('date_bapteme')->nullable();
            $table->string('telephone')->nullable();

            // --- APPROCHE SOCIALE : CATÉGORIE SOCIO-PROFESSIONNELLE ---
            // Ex: Elève (Primaire, 6ème...), Etudiant (L1, M2...), Doctorant, Employé, Non-Employé
            $table->string('niveau_etude')->nullable();
            $table->string('statut_professionnel')->nullable();

            // --- APPROCHE SPIRITUELLE : COLLÈGE DES DIRIGEANTS & STRUCTURE ---
            // Ex: Membre, Ancien, Diacre, Responsable comité, Encadreur
            $table->string('role_spirituel')->default('membre');
            // Ex: Évangélisation, Louange, Intercession, Sécurité
            $table->string('structure_mouvement')->nullable();

            // --- APPROCHE SOCIALE : STATUT MATRIMONIAL ---
            // Ex: Célibataire, Fiancé, Marié
            $table->string('statut_matrimonial')->default('célibataire');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
