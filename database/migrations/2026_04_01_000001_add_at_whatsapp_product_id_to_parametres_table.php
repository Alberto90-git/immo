<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAtWhatsappProductIdToParametresTable extends Migration
{
    public function up()
    {
        Schema::table('parametres', function (Blueprint $table) {
            $table->string('at_whatsapp_product_id', 100)->nullable()->after('at_sender_id');
        });
    }

    public function down()
    {
        Schema::table('parametres', function (Blueprint $table) {
            $table->dropColumn('at_whatsapp_product_id');
        });
    }
}
