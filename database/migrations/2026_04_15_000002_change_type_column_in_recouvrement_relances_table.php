<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeTypeColumnInRecouvrementRelancesTable extends Migration
{
    public function up()
    {
        // Supprimer la contrainte CHECK PostgreSQL sur la colonne type
        DB::statement('ALTER TABLE recouvrement_relances DROP CONSTRAINT IF EXISTS recouvrement_relances_type_check');

        // Convertir en varchar libre pour supporter rappelN dynamique
        DB::statement('ALTER TABLE recouvrement_relances ALTER COLUMN type TYPE VARCHAR(50)');
    }

    public function down()
    {
        // Remettre la contrainte d'origine (ne conserve que les valeurs existantes)
        DB::statement("ALTER TABLE recouvrement_relances ADD CONSTRAINT recouvrement_relances_type_check CHECK (type IN ('rappel1','rappel2','mise_en_demeure'))");
    }
}
