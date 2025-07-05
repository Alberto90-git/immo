<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('iddirection_ref');
            $table->foreign('iddirection_ref')->references('iddirection')->on('directions');

            $table->unsignedBigInteger('idannexe_ref');
            $table->foreign('idannexe_ref')->references('idannexes')->on('annexes');

            $table->unsignedBigInteger('locataire_id');
            $table->foreign('locataire_id')->references('id')->on('locataires');

            $table->unsignedBigInteger('maison_id');
            $table->foreign('maison_id')->references('id')->on('maisons');

            $table->unsignedBigInteger('chambre_id');
            $table->foreign('chambre_id')->references('id')->on('chambres');

            $table->dateTime('date_paiement');
            $table->string('type_paiement');
            $table->integer('montant'); 
            $table->string('mois');
            $table->string('numero_chambre');
            $table->string('type_chambre');
            $table->dateTime('delete_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factures');
    }
}
