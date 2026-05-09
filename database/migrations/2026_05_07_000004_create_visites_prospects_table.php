<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitesProspectsTable extends Migration
{
    public function up()
    {
        Schema::create('visites_prospects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddirection_ref');
            $table->foreign('iddirection_ref')->references('iddirection')->on('directions');
            $table->unsignedBigInteger('idannexe_ref')->nullable();
            $table->unsignedBigInteger('prospect_id');
            $table->foreign('prospect_id')->references('id')->on('prospects')->onDelete('cascade');
            $table->unsignedBigInteger('chambre_id')->nullable();
            $table->foreign('chambre_id')->references('id')->on('chambres')->onDelete('set null');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->dateTime('date_visite');
            $table->enum('statut', ['planifiee', 'effectuee', 'annulee'])->default('planifiee');
            $table->decimal('frais_visite', 12, 2)->default(0);
            $table->text('note')->nullable();
            $table->dateTime('delete_at')->nullable();
            $table->timestamps();

            $table->index(['iddirection_ref', 'date_visite']);
            $table->index('prospect_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('visites_prospects');
    }
}
