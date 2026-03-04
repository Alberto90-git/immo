<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagesToPublicitesTable extends Migration
{
    public function up()
    {
        Schema::table('publicites', function (Blueprint $table) {
            $table->string('image_url2')->nullable()->after('image_url');
            $table->string('image_url3')->nullable()->after('image_url2');
            $table->string('image_url4')->nullable()->after('image_url3');
            $table->datetime('published_at')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('publicites', function (Blueprint $table) {
            $table->dropColumn(['image_url2', 'image_url3', 'image_url4', 'published_at']);
        });
    }
}
