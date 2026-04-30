<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumeroQuittanceToFacturesTable extends Migration
{
    public function up()
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->string('numero_quittance', 20)->nullable()->after('type_chambre');
            $table->index('numero_quittance');
        });
    }

    public function down()
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropIndex(['numero_quittance']);
            $table->dropColumn('numero_quittance');
        });
    }
}
