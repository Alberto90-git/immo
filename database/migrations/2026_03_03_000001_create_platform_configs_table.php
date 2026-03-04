<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePlatformConfigsTable extends Migration
{
    public function up()
    {
        Schema::create('platform_configs', function (Blueprint $table) {
            $table->id();
            $table->string('kkiapay_public_key')->nullable();
            $table->string('kkiapay_private_key')->nullable();
            $table->string('kkiapay_secret_key')->nullable();
            $table->boolean('kkiapay_sandbox')->default(true);
            $table->boolean('kkiapay_enabled')->default(false);
            $table->timestamps();
        });

        // Ligne unique de configuration par défaut
        DB::table('platform_configs')->insert([
            'kkiapay_public_key'  => null,
            'kkiapay_private_key' => null,
            'kkiapay_secret_key'  => null,
            'kkiapay_sandbox'     => true,
            'kkiapay_enabled'     => false,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('platform_configs');
    }
}
