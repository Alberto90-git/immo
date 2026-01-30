<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddEssaiPlan extends Migration
{
    public function up()
    {
        DB::table('plans')->insert([
            'nom' => 'Essai',
            'code' => 'essai',
            'description' => 'Essai gratuit de 14 jours. Accès limité pour découvrir la plateforme.',
            'max_maisons' => 2,
            'max_annexes' => 0,
            'prix_annuel' => 0,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        DB::table('plans')->where('code', 'essai')->delete();
    }
}
