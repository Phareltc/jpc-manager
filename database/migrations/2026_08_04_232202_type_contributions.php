<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('type_contributions', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('code')->unique();
            $table->enum('categorie', ['mensuelle','exceptionnelle','evenement'])->default('mensuelle');
            $table->decimal('montant_defaut', 12, 2)->nullable();
            // Permet d'archiver un ancien type
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('type_contributions');
    }
};
