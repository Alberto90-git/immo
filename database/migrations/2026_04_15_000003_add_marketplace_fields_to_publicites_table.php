<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarketplaceFieldsToPublicitesTable extends Migration
{
    public function up()
    {
        Schema::table('publicites', function (Blueprint $table) {
            $table->string('ville')->nullable()->after('localisation');
            $table->string('quartier')->nullable()->after('ville');
            $table->string('type_bien')->nullable()->after('quartier'); // appartement, maison, terrain, bureau, commerce
            $table->string('slug')->nullable()->unique()->after('type_bien');
            $table->string('video_url')->nullable()->after('image_url4');
            $table->decimal('lat', 10, 7)->nullable()->after('video_url');
            $table->decimal('lng', 10, 7)->nullable()->after('lat');
            $table->text('meta_description')->nullable()->after('lng');
            $table->boolean('is_sponsored')->default(false)->after('meta_description');
            $table->dateTime('sponsored_until')->nullable()->after('is_sponsored');
            $table->string('transaction_sponsoring')->nullable()->after('sponsored_until');
        });
    }

    public function down()
    {
        Schema::table('publicites', function (Blueprint $table) {
            $table->dropColumn([
                'ville', 'quartier', 'type_bien', 'slug', 'video_url',
                'lat', 'lng', 'meta_description', 'is_sponsored',
                'sponsored_until', 'transaction_sponsoring',
            ]);
        });
    }
}
