<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtatDesLieuxPiecesTable extends Migration
{
    public function up()
    {
        Schema::create('etat_des_lieux_pieces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('etat_des_lieux_id');
            $table->string('nom_piece');
            $table->integer('ordre')->default(0);
            $table->timestamps();

            $table->foreign('etat_des_lieux_id')
                  ->references('id')->on('etats_des_lieux')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('etat_des_lieux_pieces');
    }
}
