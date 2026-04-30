<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAutomationOptOutToLocatairesTable extends Migration
{
    public function up()
    {
        Schema::table('locataires', function (Blueprint $table) {
            $table->boolean('automation_opt_out')->default(false)->after('email');
        });
    }

    public function down()
    {
        Schema::table('locataires', function (Blueprint $table) {
            $table->dropColumn('automation_opt_out');
        });
    }
}
