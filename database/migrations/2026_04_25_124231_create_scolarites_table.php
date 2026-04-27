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
        Schema::create('scolarites', function (Blueprint $table) {
            $table->id();
            // Clé étrangère liée à la table membres [cite: 21, 26]
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');

            $table->string('annee_academique'); // Ex: 2023-2024
            $table->string('etablissement');
            $table->string('niveau_etude');
            $table->enum('resultat', ['Admis', 'Échec', 'En cours'])->default('En cours');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scolarites');
    }
};
