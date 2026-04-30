<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecouvrementDossiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recouvrement_dossiers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddirection_ref');
            $table->unsignedBigInteger('locataire_id');
            $table->enum('statut', ['actif', 'apurement', 'contentieux', 'resolu', 'classe'])->default('actif');
            $table->decimal('montant_du', 12, 2)->default(0);
            $table->decimal('montant_recouvre', 12, 2)->default(0);
            $table->integer('nb_mois_impayes')->default(0);
            $table->date('date_dernier_paiement')->nullable();
            $table->text('notes_juridiques')->nullable();
            $table->date('date_assignation')->nullable();
            $table->timestamps();

            $table->foreign('locataire_id')->references('id')->on('locataires')->onDelete('cascade');
            $table->index(['iddirection_ref', 'statut']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recouvrement_dossiers');
    }
}
