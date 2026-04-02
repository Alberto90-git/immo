<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateMessagingRatesTable extends Migration
{
    public function up()
    {
        Schema::create('messaging_rates', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 5);
            $table->string('country_name', 100);
            $table->decimal('sms_unit_cost', 10, 2)->default(0);
            $table->decimal('whatsapp_unit_cost', 10, 2)->default(0);
            $table->string('currency', 10)->default('XOF');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        DB::table('messaging_rates')->insert([
            'country_code'       => 'BJ',
            'country_name'       => 'Bénin',
            'sms_unit_cost'      => 5,
            'whatsapp_unit_cost' => 10,
            'currency'           => 'XOF',
            'is_default'         => true,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('messaging_rates');
    }
}
