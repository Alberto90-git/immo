<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlanToDirectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directions', function (Blueprint $table) {
            $table->unsignedBigInteger('idplan_ref')->nullable()->after('status');
            $table->date('abonnement_debut')->nullable()->after('idplan_ref');
            $table->date('abonnement_fin')->nullable()->after('abonnement_debut');
            $table->enum('statut_abonnement', ['actif', 'expire', 'suspendu', 'essai'])->default('essai')->after('abonnement_fin');

            // Clé étrangère
            $table->foreign('idplan_ref')->references('idplan')->on('plans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directions', function (Blueprint $table) {
            $table->dropForeign(['idplan_ref']);
            $table->dropColumn(['idplan_ref', 'abonnement_debut', 'abonnement_fin', 'statut_abonnement']);
        });
    }
}
