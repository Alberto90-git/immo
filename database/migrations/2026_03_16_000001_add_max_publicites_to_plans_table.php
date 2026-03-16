<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('max_publicites')->nullable()->after('max_envois_whatsapp')
                  ->comment('Nb de publicités max — null = illimité, 0 = interdit');
        });
    }

    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('max_publicites');
        });
    }
};
