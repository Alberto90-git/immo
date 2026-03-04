<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratConfigsTable extends Migration
{
    public function up()
    {
        Schema::create('contrat_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddirection_ref')->unique();
            $table->foreign('iddirection_ref')->references('iddirection')->on('directions');
            $table->string('titre_contrat')->nullable();
            $table->text('sous_titre')->nullable();
            $table->json('articles');
            $table->dateTime('delete_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contrat_configs');
    }
}
