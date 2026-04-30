<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDelaiPaiementToRecouvrementDossiersTable extends Migration
{
    public function up()
    {
        Schema::table('recouvrement_dossiers', function (Blueprint $table) {
            $table->unsignedTinyInteger('delai_paiement')->default(8)->after('notes_juridiques');
        });
    }

    public function down()
    {
        Schema::table('recouvrement_dossiers', function (Blueprint $table) {
            $table->dropColumn('delai_paiement');
        });
    }
}
