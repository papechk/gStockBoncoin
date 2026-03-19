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
        Schema::create('resumer_journaliers', function (Blueprint $table) {
            $table->id();
            $table->decimal('totalVente', 10, 2);
            $table->decimal('totalDepense', 10, 2);
            $table->enum('mois', ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']);
            $table->integer('annee');
            $table->tinyInteger('etat')->default(1);
            $table->timestamps();
        });
    }


    /**
        id
        totalVente
        totalDepense
        mois: enum
        annee
        etat  : enum(Active/Inactive)
     */

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumer_journaliers');
    }
};
