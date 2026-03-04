<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFedapayTransactionToDirections extends Migration
{
    public function up()
    {
        Schema::table('directions', function (Blueprint $table) {
            $table->string('fedapay_transaction_id')->nullable()->after('kkiapay_transaction_id');
        });
    }

    public function down()
    {
        Schema::table('directions', function (Blueprint $table) {
            $table->dropColumn('fedapay_transaction_id');
        });
    }
}
