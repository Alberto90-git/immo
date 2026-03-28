<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('max_rappels_loyer')->nullable()->after('max_envois_whatsapp')
                  ->comment('Nb rappels loyer/mois — null = illimité, 0 = interdit');
            $table->integer('max_preavis')->nullable()->after('max_rappels_loyer')
                  ->comment('Nb préavis/mois — null = illimité, 0 = interdit');
        });
    }

    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['max_rappels_loyer', 'max_preavis']);
        });
    }
};
