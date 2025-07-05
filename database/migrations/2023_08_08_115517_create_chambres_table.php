<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChambresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chambres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddirection_ref');
            $table->foreign('iddirection_ref')->references('iddirection')->on('directions');
            $table->unsignedBigInteger('idannexe_ref');
            $table->foreign('idannexe_ref')->references('idannexes')->on('annexes');
            $table->unsignedBigInteger('maison_id');
            $table->foreign('maison_id')->references('id')->on('maisons');
            $table->string('numero_chambre');
            $table->string('type_chambre');
            $table->integer('prix_chambre');
            $table->boolean('etat');
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
        Schema::dropIfExists('chambres');
    }
}
