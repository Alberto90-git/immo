<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionsadmin = [
            'gestion-role-utilisateur',
            'gestion-role',
            'ajouter-role',
            'liste-role',
            'modifier-role',
            'gestion-utilisateur',
            'ajouter-utilisateur',
            'modifier-utilisateur',
            'desactive-utilisateur',

        ];

        $labeladmin = [
            'Gestion rôle & utilisateur',
            'Gérer rôle',
            'Ajouter un rôle',
            'Consulter les rôles',
            'Modifier  un rôle',
            'Gérer un utilisateur',
            'Ajouter un utilisateur',
            'Modifier un utilisateur',
            'Activer/Désactiver un utilisateur',

        ];


        collect($permissionsadmin)->zip(collect($labeladmin))
            ->each(fn ($item) => Permission::create(['name' => $item[0], 'label' => $item[1]]));

        foreach ($permissionsadmin as $admin) {

            Permission::where('name', $admin)->update(['group' => 'admin']);
        }

        $permissionstraitemnt = [
            'parametrage',

            'historique',

            'modifier-parametre',

            'Is_admin',
            
            'manager-contrat',
        ];


        $labelt = [
            'Paramétrage',
           
            'Historique des actions',

            'Modification parametrage',

            'Administrateur',
            
            'Gerer contrat'
        ];

        collect($permissionstraitemnt)->zip(collect($labelt))
            ->each(fn ($item) => Permission::create(['name' => $item[0], 'label' => $item[1]]));

        foreach ($permissionstraitemnt as $traiement) {
            Permission::where('name', $traiement)->update(['group' => 'params']);
        }



        $permissionstraitemnt = [
            'ajouter-publicite',

            'modifier-publicite',

            'list-publicite',

            'delete-publicite',
            
            'desactive-publicite',
        ];


        $labelt = [
            'Ajouter pub',
           
            'Modifier pub',

            'Consulter pub',

            'Suprimer pub',
            
            'Désactive pub'
        ];

        collect($permissionstraitemnt)->zip(collect($labelt))
            ->each(fn ($item) => Permission::create(['name' => $item[0], 'label' => $item[1]]));

        foreach ($permissionstraitemnt as $traiement) {
            Permission::where('name', $traiement)->update(['group' => 'pubs']);
        }



        $permissionstraitemnt = [
            'gestion-proprietaire',

            'ajoute-proprietaire',

            'Consulter-proprietaire',

            'modify-proprietaire',

            'delete-proprietaire',
        ];


        $labelt = [
            'Gestion propriétaire',

            'Ajouter propriétaire',

            'Liste propriétaire',

            'Modification propriétaire',
            
            'Supprimer propriétaire',
        ];

        collect($permissionstraitemnt)->zip(collect($labelt))
            ->each(fn ($item) => Permission::create(['name' => $item[0], 'label' => $item[1]]));

        foreach ($permissionstraitemnt as $traiement) {

            Permission::where('name', $traiement)->update(['group' => 'proprietaire']);
        }




        $permissionstraitemnt = [
            'gestion-maison',

            'ajoute-maison',

            'Consulter-maison',

            'modify-maison',

            'delete-maison',
        ];


        $labelt = [
            'Gestion maison',

            'Ajouter maison',

            'Liste maison',

            'Modification maison',
            
            'Supprimer maison',
        ];

        collect($permissionstraitemnt)->zip(collect($labelt))
            ->each(fn ($item) => Permission::create(['name' => $item[0], 'label' => $item[1]]));

        foreach ($permissionstraitemnt as $traiement) {

            Permission::where('name', $traiement)->update(['group' => 'maison']);
        }



        $permissionstraitemnt = [
            'gestion-chambre',

            'ajoute-chambre',

            'Consulter-chambre',

            'modify-chambre',

            'delete-chambre',
        ];


        $labelt = [
            'Gestion chambre',

            'Ajouter chambre',

            'Liste chambre',

            'Modification chambre',
            
            'Supprimer chambre',
        ];

        collect($permissionstraitemnt)->zip(collect($labelt))
            ->each(fn ($item) => Permission::create(['name' => $item[0], 'label' => $item[1]]));

        foreach ($permissionstraitemnt as $traiement) {

            Permission::where('name', $traiement)->update(['group' => 'chambre']);
        }




        $permissionstraitemnt = [
            'gestion-prix',

            'ajoute-prix',

            'Consulter-prix',

            'modify-prix',

            'delete-prix',
        ];


        $labelt = [
            'Gestion prix',

            'Ajouter prix',

            'Liste prix',

            'Modification prix',
            
            'Supprimer prix',
        ];

        collect($permissionstraitemnt)->zip(collect($labelt))
            ->each(fn ($item) => Permission::create(['name' => $item[0], 'label' => $item[1]]));

        foreach ($permissionstraitemnt as $traiement) {

            Permission::where('name', $traiement)->update(['group' => 'prix']);
        }




        $permissionstraitemnt = [
            'gestion-locataire',

            'ajoute-locataire',

            'Consulter-locataire',

            'modify-locataire',

            'delete-locataire',
            
            'download-recu-avance',
        ];


        $labelt = [
            'Gestion locataire',

            'Ajouter locataire',

            'Liste locataire',

            'Modification locataire',
            
            'Supprimer locataire',
            
            'Télecharger réçu avance',
        ];

        collect($permissionstraitemnt)->zip(collect($labelt))
            ->each(fn ($item) => Permission::create(['name' => $item[0], 'label' => $item[1]]));

        foreach ($permissionstraitemnt as $traiement) {

            Permission::where('name', $traiement)->update(['group' => 'locataire']);
        }




        $permissionstraitemnt = [
            'gestion-paiement',

            'ajoute-paiement',

            'Consulter-paiement',

            'modify-paiement',

            'delete-paiement',
            
            'download-recu-location',
        ];


        $labelt = [
            'Gestion paiement',

            'Ajouter paiement',

            'Liste paiement',

            'Modification paiement',
            
            'Supprimer paiement',
            
            'Télecharger réçu location',
        ];

        collect($permissionstraitemnt)->zip(collect($labelt))
            ->each(fn ($item) => Permission::create(['name' => $item[0], 'label' => $item[1]]));

        foreach ($permissionstraitemnt as $traiement) {

            Permission::where('name', $traiement)->update(['group' => 'paiement']);
        }



        $permissionstraitemnt = [
            'gestion-statistique',
            'proprio-house-chambre-locataire',
            'financier',
            'ancien-recu',
            'gestion-sta-dossier',
        ];

        $labelt = [
            'Gestion statistique',
            'Menu P/M/C/L',
            'Menu financier',
            'Menu réçu',
            'Gestion dossier',
        ];

        collect($permissionstraitemnt)->zip(collect($labelt))
            ->each(fn ($item) => Permission::create(['name' => $item[0], 'label' => $item[1]]));

        foreach ($permissionstraitemnt as $traiement) {

            Permission::where('name', $traiement)->update(['group' => 'statistique']);
        }


        $permissionstraitemnt = [
            'gestion-mot-passe',

            'action-reinitialiser',

        ];

        $labelt = [
            'Gestion mot de passe',

            'Autoriser la réinitialisation',
        ];

        collect($permissionstraitemnt)->zip(collect($labelt))
            ->each(fn ($item) => Permission::create(['name' => $item[0], 'label' => $item[1]]));

        foreach ($permissionstraitemnt as $traiement) {

            Permission::where('name', $traiement)->update(['group' => 'changePwd']);
        }




        $permissionstraitemnt = [
            'gestion-dossier',

            'dossier-client',
            'dossier-parcelle',

            'ajouter-parcelle',
            'modifier-parcelle',
            'supprimer-parcelle',
            'cloturer-parcelle',
            'consulter-parcelle',


            'ajouter-client',
            'modifier-client',
            'supprimer-client',
            'cloturer-client',
            'consulter-client',

        ];

        $labelt = [
            'Gestion des dossiers',

            'Gestion des clients',
            'Gestion des parcelles',

            'Ajouter parcelle',
            'Modifier parcelle',
            'Supprimer parcelle',
            'Cloturer parcelle',
            'Consulter liste parcelle',


            'Ajouter client',
            'Modifier client',
            'Supprimer client',
            'Cloturer client',
            'Consulter liste client',
        ];

        collect($permissionstraitemnt)->zip(collect($labelt))
            ->each(fn ($item) => Permission::create(['name' => $item[0], 'label' => $item[1]]));

        foreach ($permissionstraitemnt as $traiement) {

            Permission::where('name', $traiement)->update(['group' => 'dossiers']);
        }




        // $permissionsadminonly = [
        //     'only-admin',
        // ];

        // $labeladminonly = [
        //     'Access unique admin',
        // ];


        // collect($permissionsadminonly)->zip(collect($labeladminonly))
        //     ->each(fn ($item) => Permission::create(['name' => $item[0], 'label' => $item[1]]));

        // foreach ($permissionsadminonly as $only) {

        //     Permission::where('name', $only)->update(['group' => 'only']);
        // }
    }
}
