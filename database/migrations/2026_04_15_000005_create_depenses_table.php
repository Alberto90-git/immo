<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepensesTable extends Migration
{
    public function up()
    {
        Schema::create('depenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddirection_ref');
            $table->foreign('iddirection_ref')->references('iddirection')->on('directions');
            $table->unsignedBigInteger('idannexe_ref');
            $table->foreign('idannexe_ref')->references('idannexes')->on('annexes');
            $table->unsignedBigInteger('categorie_id')->nullable();
            $table->foreign('categorie_id')->references('id')->on('categories_depenses')->nullOnDelete();
            $table->decimal('montant', 15, 2);
            $table->date('date_depense');
            $table->text('description')->nullable();
            $table->string('type_imputation')->default('agence'); // proprietaire, maison, chambre, agence
            $table->unsignedBigInteger('proprietaire_id')->nullable();
            $table->unsignedBigInteger('maison_id')->nullable();
            $table->unsignedBigInteger('chambre_id')->nullable();
            $table->string('justificatif_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('depenses');
    }
}
