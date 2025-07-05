<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcellesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcelles', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('iddirection_ref');
            $table->foreign('iddirection_ref')->references('iddirection')->on('directions');

            $table->unsignedBigInteger('idannexe_ref');
            $table->foreign('idannexe_ref')->references('idannexes')->on('annexes');
            $table->string('nom');
            $table->string('prenom');
            $table->string('quartier');
            $table->string('superficie');
            $table->string('prix');
            $table->string('telephone');
            $table->string('etat');
            $table->string('client_acheteur')->nullable();
            $table->dateTime('delete_at')->nullable();
            $table->dateTime('status')->nullable();
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
        Schema::dropIfExists('parcelles');
    }
}
