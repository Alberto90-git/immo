<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('pre_reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddirection_ref');
            $table->foreign('iddirection_ref')->references('iddirection')->on('directions');
            $table->unsignedBigInteger('idannexe_ref')->nullable();
            $table->unsignedBigInteger('prospect_id');
            $table->foreign('prospect_id')->references('id')->on('prospects')->onDelete('cascade');
            $table->unsignedBigInteger('chambre_id');
            $table->foreign('chambre_id')->references('id')->on('chambres')->onDelete('cascade');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['active', 'expiree', 'annulee', 'convertie'])->default('active');
            $table->text('note')->nullable();
            $table->dateTime('delete_at')->nullable();
            $table->timestamps();

            $table->index(['iddirection_ref', 'statut']);
            $table->index('chambre_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pre_reservations');
    }
}
