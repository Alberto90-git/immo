<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('max_envois_email')->nullable()->after('max_annexes')
                  ->comment('Nb envois email/mois — null = illimité');
            $table->integer('max_envois_whatsapp')->nullable()->after('max_envois_email')
                  ->comment('Nb envois WhatsApp/mois — null = illimité');
        });
    }

    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['max_envois_email', 'max_envois_whatsapp']);
        });
    }
};
