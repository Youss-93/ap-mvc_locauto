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
        Schema::create('Reservation', function (Blueprint $table) {
            $table->id('id_reservation');
            $table->unsignedBigInteger('client_resa');
            $table->unsignedBigInteger('voiture_resa');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut_reservation', ['en attente', 'confirmée', 'annulée'])->default('en attente');
            $table->timestamps();

            $table->foreign('client_resa')
                  ->references('id_utilisateur')
                  ->on('Utilisateur')
                  ->onDelete('cascade');

            $table->foreign('voiture_resa')
                  ->references('id_voiture')
                  ->on('Voiture')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Reservation');
    }
};
