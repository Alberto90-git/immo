<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDevisePaysToParametresTable extends Migration
{
    public function up()
    {
        Schema::table('parametres', function (Blueprint $table) {
            // Localisation
            $table->string('pays', 2)->default('BJ')->after('timezone');          // ISO 3166-1 alpha-2
            $table->string('devise', 3)->default('XOF')->after('pays');           // ISO 4217
            $table->string('indicatif_tel', 6)->nullable()->after('devise');      // ex: +229
            $table->string('format_date', 10)->default('d/m/Y')->after('indicatif_tel');

            // Taux de change (JSON) : {"EUR":655.957,"USD":600.0,"GHS":45.0,"NGN":0.40}
            // Clé = code devise cible, valeur = combien d'unités de devise locale = 1 unité cible
            $table->json('taux_change')->nullable()->after('format_date');

            // Taxes locales (JSON) : {"tva":18,"tva_applicable":true,"nom_taxe":"TVA"}
            $table->json('taxes')->nullable()->after('taux_change');
        });
    }

    public function down()
    {
        Schema::table('parametres', function (Blueprint $table) {
            $table->dropColumn(['pays', 'devise', 'indicatif_tel', 'format_date', 'taux_change', 'taxes']);
        });
    }
}
