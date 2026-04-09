<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWhatsappContactBlocageToPlatformConfigs extends Migration
{
    public function up()
    {
        Schema::table('platform_configs', function (Blueprint $table) {
            $table->string('whatsapp_contact_blocage', 30)->nullable()->after('at_whatsapp_product_id');
        });
    }

    public function down()
    {
        Schema::table('platform_configs', function (Blueprint $table) {
            $table->dropColumn('whatsapp_contact_blocage');
        });
    }
}
