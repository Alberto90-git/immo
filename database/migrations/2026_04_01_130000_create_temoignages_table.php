<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemoignagesTable extends Migration
{
    public function up()
    {
        Schema::create('temoignages', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 80);
            $table->string('role', 100)->nullable();
            $table->text('texte');
            $table->unsignedTinyInteger('etoiles')->default(5);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('temoignages');
    }
}
