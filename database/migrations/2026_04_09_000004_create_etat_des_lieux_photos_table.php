<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtatDesLieuxPhotosTable extends Migration
{
    public function up()
    {
        Schema::create('etat_des_lieux_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('etat_des_lieux_id');
            $table->unsignedBigInteger('piece_id')->nullable();
            $table->string('chemin_fichier');
            $table->string('nom_original')->nullable();
            $table->timestamps();

            $table->foreign('etat_des_lieux_id')
                  ->references('id')->on('etats_des_lieux')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('etat_des_lieux_photos');
    }
}
