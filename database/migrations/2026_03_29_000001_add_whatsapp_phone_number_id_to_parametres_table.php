<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWhatsappPhoneNumberIdToParametresTable extends Migration
{
    public function up()
    {
        Schema::table('parametres', function (Blueprint $table) {
            $table->string('whatsapp_phone_number_id')->nullable()->after('whatsapp_api_token');
        });
    }

    public function down()
    {
        Schema::table('parametres', function (Blueprint $table) {
            $table->dropColumn('whatsapp_phone_number_id');
        });
    }
}
