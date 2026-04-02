<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagingTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('messaging_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('iddirection_ref');
            $table->enum('channel', ['sms', 'whatsapp']);
            $table->integer('recipient_count');
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 10)->default('XOF');
            $table->string('country_code', 5)->nullable();
            $table->string('payment_provider', 20)->nullable();
            $table->string('payment_transaction_id', 255)->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->boolean('messages_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('messaging_transactions');
    }
}
