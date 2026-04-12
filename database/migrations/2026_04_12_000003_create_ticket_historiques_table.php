<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketHistoriquesTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_historiques', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->string('statut_avant')->nullable();
            $table->string('statut_apres');
            $table->text('commentaire')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_historiques');
    }
}
