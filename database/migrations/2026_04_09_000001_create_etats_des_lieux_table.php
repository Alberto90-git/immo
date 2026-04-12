<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtatsDesLieuxTable extends Migration
{
    public function up()
    {
        Schema::create('etats_des_lieux', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddirection_ref');
            $table->unsignedBigInteger('idannexe_ref')->nullable();
            $table->unsignedBigInteger('locataire_id');
            $table->unsignedBigInteger('maison_id');
            $table->unsignedBigInteger('chambre_id');
            $table->enum('type', ['entree', 'sortie'])->default('entree');
            $table->date('date_etat');
            $table->unsignedBigInteger('etat_entree_id')->nullable()->comment('Lien vers l etat entree pour le type sortie');
            $table->enum('statut', ['brouillon', 'finalise', 'signe'])->default('brouillon');
            $table->text('notes_generales')->nullable();
            $table->decimal('retenue_caution', 12, 2)->default(0);
            $table->string('signature_token', 80)->nullable()->unique();
            $table->boolean('signe_locataire')->default(false);
            $table->timestamp('signe_locataire_at')->nullable();
            $table->boolean('signe_agent')->default(false);
            $table->timestamp('signe_agent_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('delete_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('etats_des_lieux');
    }
}
