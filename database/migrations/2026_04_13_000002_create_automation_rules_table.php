<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutomationRulesTable extends Migration
{
    public function up()
    {
        Schema::create('automation_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddirection_ref');
            $table->unsignedBigInteger('idannexe_ref')->nullable();

            // Type de règle
            $table->enum('type', [
                'rappel_loyer',       // Rappel de paiement loyer
                'escalade_impaye',    // Escalade impayé (email → SMS → WA)
                'preavis_bail',       // Préavis fin de bail
                'rapport_mensuel',    // Rapport mensuel propriétaire
                'renouvellement',     // Renouvellement contrat
            ]);

            // Configuration générale
            $table->boolean('actif')->default(true);
            $table->string('heure_envoi', 5)->default('08:00'); // HH:MM

            // Rappel loyer : jours relatifs à la date d'échéance
            // Ex: [-5, 0, 3] → J-5, J-0, J+3 (stocké en JSON)
            $table->json('jours_declenchement')->nullable(); // [-5, 0, 3]

            // Canal d'envoi
            $table->enum('canal', ['email', 'sms', 'whatsapp', 'email_sms', 'email_whatsapp', 'tous'])->default('email');

            // Escalade impayé : délai entre chaque étape (jours)
            $table->unsignedInteger('delai_escalade_sms')->nullable();      // ex: 3 jours après email
            $table->unsignedInteger('delai_escalade_whatsapp')->nullable(); // ex: 5 jours après SMS

            // Préavis / Renouvellement : combien de mois avant l'échéance
            $table->unsignedInteger('mois_avant_echeance')->nullable(); // ex: 2

            // Rapport mensuel : jour du mois
            $table->unsignedInteger('jour_du_mois')->default(1);

            $table->timestamps();

            $table->index(['iddirection_ref', 'type', 'actif']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('automation_rules');
    }
}
