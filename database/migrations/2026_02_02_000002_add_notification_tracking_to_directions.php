<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationTrackingToDirections extends Migration
{
    public function up()
    {
        Schema::table('directions', function (Blueprint $table) {
            $table->string('derniere_notification_abonnement')->nullable()->after('statut_abonnement');
        });
    }

    public function down()
    {
        Schema::table('directions', function (Blueprint $table) {
            $table->dropColumn('derniere_notification_abonnement');
        });
    }
}
