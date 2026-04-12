<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtatDesLieuxElementsTable extends Migration
{
    public function up()
    {
        Schema::create('etat_des_lieux_elements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('piece_id');
            $table->string('element'); // Sol, Murs, Plafond, Portes/Fenêtres, Équipements, Électricité
            $table->enum('etat', ['tres_bon', 'bon', 'moyen', 'mauvais', 'hors_service'])->default('bon');
            $table->text('description')->nullable();
            $table->boolean('degradation_detectee')->default(false);
            $table->decimal('montant_retenue', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('piece_id')
                  ->references('id')->on('etat_des_lieux_pieces')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('etat_des_lieux_elements');
    }
}
