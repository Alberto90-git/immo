<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixRolesUniqueConstraintForMultitenant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Supprimer la contrainte unique globale (name, guard_name)
        // et la remplacer par (name, guard_name, iddirectionRef_role)
        // pour permettre à chaque direction d'avoir son propre rôle "Administrateur"
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique('roles_name_guard_name_unique');
            $table->unique(['name', 'guard_name', 'iddirectionRef_role'], 'roles_name_guard_direction_unique');
        });
    }

    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique('roles_name_guard_direction_unique');
            $table->unique(['name', 'guard_name'], 'roles_name_guard_name_unique');
        });
    }
}
