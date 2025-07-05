<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
use App\Parametre;
use App\Direction;
use App\Annexe;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $direction = Direction::create([
            'designation'               => 'All Digital Agency',
            'telephone'            => '94664858',
            'email'            => 'alldigitalagency90@gmail.com',
            'siege_social'            => 'Ab-calavi',
        ]);

        $annexe = Annexe::create([
            'iddirection_ref' =>1,
            'designation'               => 'All Digital Agency',
            'telephone'            => '94664858',
            'email'            => 'alldigitalagency90@gmail.com',
            'siege_social'            => 'Ab-calavi',
        ]);

        $user = User::create([
            'nom'               => 'TCHEGNON',
            'prenom'            => 'Albert',
            'grade'            => 'Directeur Général',
            'email'             => 'admin@immo.net',
            'password'          => Hash::make('password'),
            'is_admin'          => false,
            'email_verified_at'    => Carbon::now(),
            'last_login'       => Carbon::now(),
            'iddirection_ref' => 1,
            'idannexe_ref' => 1,
            'type_compte' => 'Particulier'
        ]);



        $role = Role::create(['name' => 'Super Admin','iddirectionRef_role' =>1]);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
