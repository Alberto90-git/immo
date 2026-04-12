<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestatairesTable extends Migration
{
    public function up()
    {
        Schema::create('prestataires', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddirection_ref');
            $table->unsignedBigInteger('idannexe_ref')->nullable();
            $table->string('nom');
            $table->enum('specialite', [
                'plomberie','electricite','serrurerie','menuiserie',
                'climatisation','maconnerie','peinture','autre',
            ])->default('autre');
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('adresse')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamp('delete_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prestataires');
    }
}
