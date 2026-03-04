<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommunicationConfigToParametresTable extends Migration
{
    public function up()
    {
        Schema::table('parametres', function (Blueprint $table) {
            $table->string('email_envoi')->nullable()->after('logo_url');
            $table->string('whatsapp_api_url')->nullable()->after('email_envoi');
            $table->string('whatsapp_api_token')->nullable()->after('whatsapp_api_url');
            $table->string('whatsapp_numero_envoi')->nullable()->after('whatsapp_api_token');
        });
    }

    public function down()
    {
        Schema::table('parametres', function (Blueprint $table) {
            $table->dropColumn(['email_envoi', 'whatsapp_api_url', 'whatsapp_api_token', 'whatsapp_numero_envoi']);
        });
    }
}
