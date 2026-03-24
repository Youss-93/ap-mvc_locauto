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
        Schema::create('Voiture', function (Blueprint $table) {
            $table->id('id_voiture');
            $table->unsignedBigInteger('id_admin')->nullable();
            $table->string('modele', 50);
            $table->string('marque', 50);
            $table->integer('année');
            $table->decimal('prix_jour', 10, 2);
            $table->decimal('caution', 10, 2);
            $table->boolean('disponibilité')->default(true);
            $table->string('image_loc', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_admin')
                  ->references('id_utilisateur')
                  ->on('Utilisateur')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Voiture');
    }
};
