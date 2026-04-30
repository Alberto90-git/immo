<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSignatureImageToEtatsDesLieuxTable extends Migration
{
    public function up()
    {
        Schema::table('etats_des_lieux', function (Blueprint $table) {
            $table->longText('signature_locataire_image')->nullable()->after('signe_locataire_at');
        });
    }

    public function down()
    {
        Schema::table('etats_des_lieux', function (Blueprint $table) {
            $table->dropColumn('signature_locataire_image');
        });
    }
}
