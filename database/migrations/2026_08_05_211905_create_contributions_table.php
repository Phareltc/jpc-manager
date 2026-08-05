<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();

            // Clés étrangères
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->foreignId('type_contribution_id')->nullable()->constrained('type_contributions')->onDelete('restrict');

            $table->decimal('montant', 12, 2); // FCFA
            $table->date('date_paiement');
            $table->integer('annee_concernee'); // Ex: 2026
            $table->integer('mois_concerne')->nullable(); // Ex: 1 à 12
            $table->enum('mode_paiement', ['Espèces', 'Mobile Money', 'Virement', 'Chèque'])->default('Espèces');
            $table->text('observation')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};