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
        Schema::create('historiques', function (Blueprint $table) {
            $table->id();
            $table->string('model_name');
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('user_id');
            $table->string('action');
            $table->date('date');
            $table->json('valeur_avant')->nullable();
            $table->json('valeur_apres')->nullable();
            $table->timestamps();
        });
    }

    /**
        id
        model_name
        model_id
        user_id
        action
        date
        valeur _avant :  json
        valeur _avant :  json

     */



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historiques');
    }
};
