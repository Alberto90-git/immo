<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutomationLogsTable extends Migration
{
    public function up()
    {
        Schema::create('automation_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('automation_rule_id')->nullable();
            $table->unsignedBigInteger('iddirection_ref');
            $table->unsignedBigInteger('locataire_id')->nullable();
            $table->unsignedBigInteger('proprietaire_id')->nullable();

            $table->enum('type', [
                'rappel_loyer',
                'escalade_impaye',
                'preavis_bail',
                'rapport_mensuel',
                'renouvellement',
            ]);

            $table->enum('canal', ['email', 'sms', 'whatsapp']);
            $table->enum('statut', ['succes', 'echec', 'ignore'])->default('succes');
            $table->text('message')->nullable();    // Détail ou erreur
            $table->string('destinataire')->nullable(); // email ou téléphone
            $table->string('reference')->nullable();   // ex: "Loyer Janvier 2026"

            $table->timestamp('envoye_le')->useCurrent();
            $table->timestamps();

            $table->index(['iddirection_ref', 'type', 'envoye_le']);
            $table->index(['locataire_id', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('automation_logs');
    }
}
