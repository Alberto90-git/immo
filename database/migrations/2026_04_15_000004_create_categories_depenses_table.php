<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesDepensesTable extends Migration
{
    public function up()
    {
        Schema::create('categories_depenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddirection_ref');
            $table->foreign('iddirection_ref')->references('iddirection')->on('directions');
            $table->string('nom');
            $table->string('type')->default('autre'); // entretien, taxes, honoraires, travaux, autre
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories_depenses');
    }
}
