<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsMaintenanceTable extends Migration
{
    public function up()
    {
        Schema::create('tickets_maintenance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddirection_ref');
            $table->unsignedBigInteger('idannexe_ref')->nullable();
            $table->unsignedBigInteger('maison_id')->nullable();
            $table->unsignedBigInteger('chambre_id')->nullable();
            $table->unsignedBigInteger('locataire_id')->nullable();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->enum('categorie', [
                'plomberie','electricite','serrurerie','menuiserie',
                'climatisation','maconnerie','peinture','autre',
            ])->default('autre');
            $table->enum('priorite', ['basse','normale','haute','urgente'])->default('normale');
            $table->enum('statut', ['nouveau','en_cours','resolu','cloture'])->default('nouveau');
            $table->unsignedBigInteger('prestataire_id')->nullable();
            $table->decimal('cout_intervention', 12, 2)->nullable();
            $table->enum('imputation', ['proprietaire','agence'])->nullable();
            $table->date('date_ouverture');
            $table->date('date_resolution')->nullable();
            $table->date('date_cloture')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('delete_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets_maintenance');
    }
}
