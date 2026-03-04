<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pourcentage_gestions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddirection_ref');
            $table->foreign('iddirection_ref')->references('iddirection')->on('directions');
            $table->string('type'); // 'general' ou 'groupe'
            $table->string('nom')->nullable();
            $table->decimal('pourcentage', 5, 2);
            $table->boolean('is_active')->default(false);
            $table->dateTime('delete_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pourcentage_gestions');
    }
};
