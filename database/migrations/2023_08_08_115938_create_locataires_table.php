<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocatairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locataires', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('iddirection_ref');
            $table->foreign('iddirection_ref')->references('iddirection')->on('directions');

            $table->unsignedBigInteger('idannexe_ref');
            $table->foreign('idannexe_ref')->references('idannexes')->on('annexes');

            $table->unsignedBigInteger('maison_id');
            $table->foreign('maison_id')->references('id')->on('maisons');

            $table->unsignedBigInteger('chambre_id');
            $table->foreign('chambre_id')->references('id')->on('chambres');

            $table->date('date_entree');
            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone');
            $table->string('profession');
            $table->string('quartier')->nullable();
            $table->integer('nombre_avance');
            $table->integer('nombre_avance_consomme');
            $table->integer('prix_mois');
            $table->integer('caution_courant');
            $table->integer('caution_eau');
            $table->dateTime('delete_at')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('locataires');
    }
}
