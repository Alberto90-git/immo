<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailToLocatairesAndProprietaires extends Migration
{
    public function up()
    {
        Schema::table('locataires', function (Blueprint $table) {
            $table->string('email')->nullable()->after('telephone');
        });

        Schema::table('proprietaires', function (Blueprint $table) {
            $table->string('email')->nullable()->after('telephone');
        });
    }

    public function down()
    {
        Schema::table('locataires', function (Blueprint $table) {
            $table->dropColumn('email');
        });

        Schema::table('proprietaires', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
}
