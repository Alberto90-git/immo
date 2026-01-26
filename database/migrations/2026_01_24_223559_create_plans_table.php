<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id('idplan');
            $table->string('nom');
            $table->string('code')->unique(); // starter, standard, premium
            $table->text('description')->nullable();
            $table->integer('max_maisons')->default(0); // 0 = illimité
            $table->integer('max_annexes')->default(0); // 0 = pas d'annexes autorisées
            $table->decimal('prix_annuel', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->datetime('delete_at')->nullable();
        });

        // Insérer les plans par défaut
        DB::table('plans')->insert([
            [
                'nom' => 'Starter',
                'code' => 'starter',
                'description' => 'Gérer au plus 5 maisons dans le système. Pas de possibilité de créer des annexes.',
                'max_maisons' => 5,
                'max_annexes' => 0,
                'prix_annuel' => 54000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Standard',
                'code' => 'standard',
                'description' => 'Gérer au plus 20 maisons. Pas de possibilité de créer des annexes.',
                'max_maisons' => 20,
                'max_annexes' => 0,
                'prix_annuel' => 120000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Premium',
                'code' => 'premium',
                'description' => 'Accès illimité à toutes les ressources. Créer au plus 2 annexes.',
                'max_maisons' => 0, // 0 = illimité
                'max_annexes' => 2,
                'prix_annuel' => 180000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
