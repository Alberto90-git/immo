<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class EvolveContratConfigsTable extends Migration
{
    public function up()
    {
        Schema::table('contrat_configs', function (Blueprint $table) {
            $table->dropUnique(['iddirection_ref']);
            $table->index('iddirection_ref');
            $table->string('nom_modele', 100)->nullable()->default('Modèle par défaut');
            $table->enum('type_bail', ['meuble', 'nu', 'commercial', 'autre'])->nullable()->default('autre');
            $table->boolean('is_default')->default(false);
            $table->index(['iddirection_ref', 'type_bail']);
        });

        // Marquer le premier template de chaque direction comme défaut
        DB::statement('UPDATE contrat_configs SET is_default = true WHERE id IN (SELECT min_id FROM (SELECT MIN(id) as min_id FROM contrat_configs GROUP BY iddirection_ref) t)');
    }

    public function down()
    {
        Schema::table('contrat_configs', function (Blueprint $table) {
            $table->dropIndex(['iddirection_ref', 'type_bail']);
            $table->dropIndex(['iddirection_ref']);
            $table->dropColumn(['nom_modele', 'type_bail', 'is_default']);
        });
        // Note: restore UNIQUE is destructive if duplicates exist
        Schema::table('contrat_configs', function (Blueprint $table) {
            $table->unique('iddirection_ref');
        });
    }
}
