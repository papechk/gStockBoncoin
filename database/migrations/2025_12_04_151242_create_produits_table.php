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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->decimal('prix_achat', 10, 2);
            $table->decimal('prix_vente', 10, 2);
            $table->integer('quantite_stock')->default(0);
            $table->foreignId('categorie_id')->constrained('categories')->cascadeOnDelete();
            $table->string('image')->nullable();
            $table->string('code_barre')->unique()->nullable();
            $table->integer('seuil_alerte')->default(10);
            $table->timestamps();
        });
    }
    // etat = tinyint (0 = inactif, 1 = actif)

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
