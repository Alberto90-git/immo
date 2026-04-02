<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAfricastalkingConfigToParametresTable extends Migration
{
    public function up()
    {
        Schema::table('parametres', function (Blueprint $table) {
            $table->string('at_username', 100)->nullable()->after('whatsapp_phone_number_id');
            $table->string('at_api_key', 500)->nullable()->after('at_username');
            $table->string('at_sender_id', 50)->nullable()->after('at_api_key');
        });
    }

    public function down()
    {
        Schema::table('parametres', function (Blueprint $table) {
            $table->dropColumn(['at_username', 'at_api_key', 'at_sender_id']);
        });
    }
}
