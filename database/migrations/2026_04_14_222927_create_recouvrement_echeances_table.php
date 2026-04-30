<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecouvrementEcheancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recouvrement_echeances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dossier_id');
            $table->integer('numero');
            $table->decimal('montant', 12, 2);
            $table->date('date_echeance');
            $table->enum('statut', ['en_attente', 'paye', 'en_retard'])->default('en_attente');
            $table->date('date_paiement')->nullable();
            $table->timestamps();

            $table->foreign('dossier_id')->references('id')->on('recouvrement_dossiers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recouvrement_echeances');
    }
}
