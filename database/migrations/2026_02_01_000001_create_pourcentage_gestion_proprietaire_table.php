<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pourcentage_gestion_proprietaire', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pourcentage_gestion_id');
            $table->foreign('pourcentage_gestion_id')
                  ->references('id')
                  ->on('pourcentage_gestions')
                  ->onDelete('cascade');
            $table->unsignedBigInteger('proprietaire_id');
            $table->foreign('proprietaire_id')
                  ->references('id')
                  ->on('proprietaires')
                  ->onDelete('cascade');
            $table->timestamps();
            $table->unique(['proprietaire_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pourcentage_gestion_proprietaire');
    }
};
