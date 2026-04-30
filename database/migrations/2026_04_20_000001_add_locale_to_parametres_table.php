<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocaleToParametresTable extends Migration
{
    public function up()
    {
        Schema::table('parametres', function (Blueprint $table) {
            $table->string('locale', 5)->default('fr')->after('devise');
        });
    }

    public function down()
    {
        Schema::table('parametres', function (Blueprint $table) {
            $table->dropColumn('locale');
        });
    }
}
