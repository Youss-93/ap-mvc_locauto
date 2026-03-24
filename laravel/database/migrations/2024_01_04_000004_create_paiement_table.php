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
        Schema::create('Paiement', function (Blueprint $table) {
            $table->id('id_paiement');
            $table->unsignedBigInteger('paiement_resa');
            $table->decimal('montant', 10, 2);
            $table->enum('mode_paiement', ['par Carte', 'Par Virement', 'Paypal']);
            $table->enum('statut_paiement', ['en attente', 'validée', 'échoué'])->default('en attente');
            $table->timestamp('date_paiement')->useCurrent();
            $table->timestamps();

            $table->foreign('paiement_resa')
                  ->references('id_reservation')
                  ->on('Reservation')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Paiement');
    }
};
