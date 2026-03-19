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
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boutique_id')->constrained('boutiques')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->restrictOnDelete();
            $table->string('numero_vente')->unique();
            $table->dateTime('date_vente');
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('montant_paye', 10, 2)->default(0);
            $table->decimal('montant_rendu', 10, 2)->default(0);
            $table->enum('statut', ['en_cours', 'termine', 'annule'])->default('termine');
            $table->enum('mode_paiement', ['especes', 'carte', 'mobile', 'credit'])->default('especes');
            $table->timestamps();
        });
    }

    /**
        ventes
- id
- client_id (FK)
- user_id (employé qui vend)
- date_vente
- total
client_id → ON DELETE RESTRICT
user_id   → ON DELETE SET NULL


     */

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};
