<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMessagingEnabledToPlansTable extends Migration
{
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->boolean('sms_enabled')->default(true)->after('max_preavis');
            $table->boolean('whatsapp_enabled')->default(true)->after('sms_enabled');
        });
    }

    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['sms_enabled', 'whatsapp_enabled']);
        });
    }
}
