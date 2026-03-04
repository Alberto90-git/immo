<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvoisDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('envois_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddirection_ref');
            $table->string('destinataire_type'); // 'locataire' | 'proprietaire'
            $table->unsignedBigInteger('destinataire_id');
            $table->string('destinataire_nom');
            $table->string('destinataire_contact')->nullable();
            $table->string('type_document'); // 'contrat' | 'quittance_mensuelle' | 'quittance_caution' | 'releve_proprietaire' | 'releve_agence'
            $table->unsignedBigInteger('document_ref_id')->nullable();
            $table->string('methode_envoi'); // 'email' | 'whatsapp'
            $table->string('statut'); // 'success' | 'error'
            $table->text('message_erreur')->nullable();
            $table->string('pdf_temp_path')->nullable();
            $table->text('message_personnalise')->nullable();
            $table->unsignedBigInteger('envoye_par')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('envois_documents');
    }
}
