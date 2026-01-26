<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoAndCashElectroniqueToAnnexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('annexes', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('siege_social');
            $table->text('cash_electronique')->nullable()->after('logo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('annexes', function (Blueprint $table) {
            $table->dropColumn(['logo', 'cash_electronique']);
        });
    }
}
