<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecouvrementRelancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recouvrement_relances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dossier_id');
            $table->unsignedBigInteger('iddirection_ref');
            $table->enum('type', ['rappel1', 'rappel2', 'mise_en_demeure']);
            $table->enum('canal', ['email', 'sms', 'whatsapp', 'courrier'])->default('email');
            $table->enum('statut', ['envoye', 'echec', 'simule'])->default('envoye');
            $table->text('message')->nullable();
            $table->timestamp('envoye_le')->useCurrent();
            $table->timestamps();

            $table->foreign('dossier_id')->references('id')->on('recouvrement_dossiers')->onDelete('cascade');
            $table->index(['dossier_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recouvrement_relances');
    }
}
