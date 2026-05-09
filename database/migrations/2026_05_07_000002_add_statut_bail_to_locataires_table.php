<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatutBailToLocatairesTable extends Migration
{
    public function up()
    {
        Schema::table('locataires', function (Blueprint $table) {
            $table->enum('statut_bail', ['en_cours', 'signe', 'en_litige', 'resilie'])->nullable()->default('en_cours');
            $table->index('statut_bail');
        });
    }

    public function down()
    {
        Schema::table('locataires', function (Blueprint $table) {
            $table->dropIndex(['statut_bail']);
            $table->dropColumn('statut_bail');
        });
    }
}
