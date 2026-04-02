<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAfricastalkingToPlatformConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('platform_configs', function (Blueprint $table) {
            $table->string('at_username', 100)->nullable()->after('active_payment_provider');
            $table->string('at_api_key', 500)->nullable()->after('at_username');
            $table->string('at_sender_id', 50)->nullable()->after('at_api_key');
            $table->string('at_whatsapp_product_id', 100)->nullable()->after('at_sender_id');
        });
    }

    public function down()
    {
        Schema::table('platform_configs', function (Blueprint $table) {
            $table->dropColumn(['at_username', 'at_api_key', 'at_sender_id', 'at_whatsapp_product_id']);
        });
    }
}
