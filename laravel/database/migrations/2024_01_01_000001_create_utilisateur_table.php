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
        Schema::create('Utilisateur', function (Blueprint $table) {
            $table->id('id_utilisateur');
            $table->string('nom_utilisateur', 50);
            $table->string('prenom_utilisateur', 50);
            $table->string('email', 90)->unique();
            $table->string('mdp_utilisateur', 255);
            $table->string('num_tel', 10);
            $table->enum('role_utilisateur', ['admin', 'client'])->default('client');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Utilisateur');
    }
};
