<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKkiapayTransactionToDirections extends Migration
{
    public function up()
    {
        Schema::table('directions', function (Blueprint $table) {
            $table->string('kkiapay_transaction_id')->nullable()->after('statut_abonnement');
        });
    }

    public function down()
    {
        Schema::table('directions', function (Blueprint $table) {
            $table->dropColumn('kkiapay_transaction_id');
        });
    }
}
