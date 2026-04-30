<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignatureAuditsTable extends Migration
{
    public function up()
    {
        Schema::create('signature_audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('signature_demande_id');
            $table->string('action'); // created, sent_email, sent_sms, viewed, signed, certificate_generated, annule
            $table->string('canal')->nullable(); // email, sms, web, admin
            $table->string('ip_adresse', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('details')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('signature_demande_id')
                  ->references('id')->on('signature_demandes')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('signature_audits');
    }
}
