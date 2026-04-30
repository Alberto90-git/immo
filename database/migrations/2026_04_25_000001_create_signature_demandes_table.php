<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignatureDemandesTable extends Migration
{
    public function up()
    {
        Schema::create('signature_demandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddirection_ref');
            $table->unsignedBigInteger('idannexe_ref')->nullable();

            // Quel type de document
            $table->enum('document_type', ['contrat', 'etat_des_lieux', 'quittance', 'autre'])->default('autre');
            $table->unsignedBigInteger('document_id')->nullable();
            $table->string('document_titre');
            $table->text('document_description')->nullable();

            // PDF original à faire signer (stocké dans storage)
            $table->string('pdf_path')->nullable();
            // Empreinte SHA-256 du PDF avant signature
            $table->string('sha256_document', 64)->nullable();

            // Signataire
            $table->unsignedBigInteger('locataire_id')->nullable();
            $table->string('signataire_nom');
            $table->string('signataire_email')->nullable();
            $table->string('signataire_telephone')->nullable();

            // Token et statut
            $table->string('token', 80)->unique();
            $table->enum('statut', ['en_attente', 'signe', 'expire', 'annule'])->default('en_attente');
            $table->timestamp('expires_at')->nullable();

            // Données de signature
            $table->longText('signature_image')->nullable();
            $table->timestamp('signe_at')->nullable();
            $table->string('ip_adresse', 45)->nullable();
            $table->text('user_agent')->nullable();

            // PDF signé (après fusion)
            $table->string('pdf_signe_path')->nullable();
            // Empreinte SHA-256 du PDF après signature
            $table->string('sha256_signe', 64)->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('delete_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('signature_demandes');
    }
}
