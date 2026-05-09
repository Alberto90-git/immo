<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProspectsTable extends Migration
{
    public function up()
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddirection_ref');
            $table->foreign('iddirection_ref')->references('iddirection')->on('directions');
            $table->unsignedBigInteger('idannexe_ref')->nullable();
            $table->string('nom');
            $table->string('prenom')->nullable();
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('maison_id')->nullable();
            $table->foreign('maison_id')->references('id')->on('maisons')->onDelete('set null');
            $table->unsignedBigInteger('chambre_id')->nullable();
            $table->foreign('chambre_id')->references('id')->on('chambres')->onDelete('set null');
            $table->text('message')->nullable();
            $table->text('note')->nullable();
            $table->enum('statut', ['demande', 'visite_planifiee', 'visite_effectuee', 'dossier', 'converti', 'annule'])->default('demande');
            $table->enum('source', ['public', 'agent'])->default('agent');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->dateTime('delete_at')->nullable();
            $table->timestamps();

            $table->index(['iddirection_ref', 'statut']);
            $table->index('idannexe_ref');
        });
    }

    public function down()
    {
        Schema::dropIfExists('prospects');
    }
}
