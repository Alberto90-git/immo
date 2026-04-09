<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
use App\Direction;
use App\Annexe;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        // ── Direction (entreprise du propriétaire de l'appli) ─────────────────
        $direction = Direction::create([
            'designation'  => 'All Digital Agency',
            'telephone'    => '94664858',
            'email'        => 'alldigitalagency90@gmail.com',
            'siege_social' => 'Abomey-Calavi',
        ]);

        // ── Annexe principale ─────────────────────────────────────────────────
        $annexe = Annexe::create([
            'iddirection_ref' => $direction->iddirection,
            'designation'     => 'All Digital Agency',
            'telephone'       => '94664858',
            'email'           => 'alldigitalagency90@gmail.com',
            'siege_social'    => 'Abomey-Calavi',
        ]);

        // ── Compte propriétaire de l'application ──────────────────────────────
        $user = User::create([
            'nom'               => 'TCHEGNON',
            'prenom'            => 'Albert',
            'grade'             => 'Directeur Général',
            'email'             => 'alberttchegnon4@gmail.com',
            'password'          => Hash::make('password'),
            'is_admin'          => false,
            'email_verified_at' => Carbon::now(),
            'last_login'        => Carbon::now(),
            'iddirection_ref'   => $direction->iddirection,
            'idannexe_ref'      => $annexe->idannexes,
            'type_compte'       => 'Particulier',
        ]);

        // ── Rôle Super Admin — toutes les permissions SAUF config-paiement ────
        // config-paiement est réservé au propriétaire de l'appli uniquement,
        // assigné directement sur l'utilisateur (pas via rôle).
        $role = Role::create([
            'name'                => 'Super Admin',
            'iddirectionRef_role' => $direction->iddirection,
        ]);

        $permissions = Permission::where('name', '!=', 'config-paiement')
                                  ->pluck('id', 'id')
                                  ->all();
        $role->syncPermissions($permissions);

        $user->assignRole($role);

        // ── config-paiement : assigné directement au user (pas au rôle) ───────
        $user->givePermissionTo('config-paiement');

        // ── Génération du cachet et du logo par défaut ────────────────────────
        try {
            $cachetService = new \App\Services\CachetGeneratorService();
            $cachetPath    = $cachetService->generate($direction->designation, $direction->iddirection);

            $logoService = new \App\Services\LogoGeneratorService();
            $logoPath    = $logoService->generate($direction->designation, $direction->iddirection);

            DB::table('parametres')->insert([
                'iddirection_ref'       => $direction->iddirection,
                'cash_electronique_url' => $cachetPath ?? '',
                'logo_url'              => $logoPath ?? '',
                'format_choisi'         => 'default',
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);
        } catch (\Exception $e) {
            // Non bloquant
            \Illuminate\Support\Facades\Log::warning('Seeder cachet/logo generation failed: ' . $e->getMessage());
        }
    }
}
