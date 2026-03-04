<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddFedapayToPlatformConfigs extends Migration
{
    public function up()
    {
        Schema::table('platform_configs', function (Blueprint $table) {
            $table->string('fedapay_public_key')->nullable()->after('kkiapay_enabled');
            $table->string('fedapay_secret_key')->nullable()->after('fedapay_public_key');
            $table->boolean('fedapay_sandbox')->default(true)->after('fedapay_secret_key');
            // Source de vérité unique : quel prestataire est actif
            $table->string('active_payment_provider')->default('none')->after('fedapay_sandbox');
        });

        // Backfill : si kkiapay_enabled était déjà activé, le refléter dans active_payment_provider
        DB::table('platform_configs')
            ->where('kkiapay_enabled', 1)
            ->update(['active_payment_provider' => 'kkiapay']);
    }

    public function down()
    {
        Schema::table('platform_configs', function (Blueprint $table) {
            $table->dropColumn(['fedapay_public_key', 'fedapay_secret_key', 'fedapay_sandbox', 'active_payment_provider']);
        });
    }
}
