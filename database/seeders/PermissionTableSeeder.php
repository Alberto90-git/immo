<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        // ── Définition complète des permissions par groupe ────────────────────
        // Format : 'nom' => ['label' => '...', 'group' => '...']
        $allPermissions = [

            // ── Gestion fonction & utilisateur ────────────────────────────────
            'gestion-role-utilisateur' => ['label' => 'Gestion fonction & utilisateur', 'group' => 'admin'],
            'gestion-role'             => ['label' => 'Gérer fonction',                  'group' => 'admin'],
            'ajouter-role'             => ['label' => 'Ajouter une fonction',            'group' => 'admin'],
            'liste-role'               => ['label' => 'Consulter les fonctions',         'group' => 'admin'],
            'modifier-role'            => ['label' => 'Modifier une fonction',           'group' => 'admin'],
            'gestion-utilisateur'      => ['label' => 'Gérer un utilisateur',            'group' => 'admin'],
            'ajouter-utilisateur'      => ['label' => 'Ajouter un utilisateur',         'group' => 'admin'],
            'liste-utilisateur'        => ['label' => 'Consulter les utilisateurs',     'group' => 'admin'],
            'modifier-utilisateur'     => ['label' => 'Modifier un utilisateur',        'group' => 'admin'],
            'desactive-utilisateur'    => ['label' => 'Activer/Désactiver un utilisateur', 'group' => 'admin'],

            // ── Paramétrage ───────────────────────────────────────────────────
            'parametrage'       => ['label' => 'Paramétrage',              'group' => 'params'],
            'historique'        => ['label' => 'Historique des actions',   'group' => 'params'],
            'modifier-parametre'=> ['label' => 'Modification paramétrage', 'group' => 'params'],
            'Is_admin'          => ['label' => 'Administrateur',           'group' => 'params'],
            'manager-contrat'   => ['label' => 'Gérer contrat',            'group' => 'params'],

            // ── Propriétaire ──────────────────────────────────────────────────
            'gestion-proprietaire'   => ['label' => 'Gestion propriétaire',    'group' => 'proprietaire'],
            'ajoute-proprietaire'    => ['label' => 'Ajouter propriétaire',    'group' => 'proprietaire'],
            'Consulter-proprietaire' => ['label' => 'Liste propriétaire',      'group' => 'proprietaire'],
            'modify-proprietaire'    => ['label' => 'Modification propriétaire','group' => 'proprietaire'],
            'delete-proprietaire'    => ['label' => 'Supprimer propriétaire',  'group' => 'proprietaire'],

            // ── Maison ────────────────────────────────────────────────────────
            'gestion-maison'   => ['label' => 'Gestion maison',    'group' => 'maison'],
            'ajoute-maison'    => ['label' => 'Ajouter maison',    'group' => 'maison'],
            'Consulter-maison' => ['label' => 'Liste maison',      'group' => 'maison'],
            'modify-maison'    => ['label' => 'Modification maison','group' => 'maison'],
            'delete-maison'    => ['label' => 'Supprimer maison',  'group' => 'maison'],

            // ── Chambre ───────────────────────────────────────────────────────
            'gestion-chambre'   => ['label' => 'Gestion chambre',    'group' => 'chambre'],
            'ajoute-chambre'    => ['label' => 'Ajouter chambre',    'group' => 'chambre'],
            'Consulter-chambre' => ['label' => 'Liste chambre',      'group' => 'chambre'],
            'modify-chambre'    => ['label' => 'Modification chambre','group' => 'chambre'],
            'delete-chambre'    => ['label' => 'Supprimer chambre',  'group' => 'chambre'],

            // ── Prix ──────────────────────────────────────────────────────────
            'gestion-prix'   => ['label' => 'Gestion prix',    'group' => 'prix'],
            'ajoute-prix'    => ['label' => 'Ajouter prix',    'group' => 'prix'],
            'Consulter-prix' => ['label' => 'Liste prix',      'group' => 'prix'],
            'modify-prix'    => ['label' => 'Modification prix','group' => 'prix'],
            'delete-prix'    => ['label' => 'Supprimer prix',  'group' => 'prix'],

            // ── Locataire ─────────────────────────────────────────────────────
            'gestion-locataire'    => ['label' => 'Gestion locataire',      'group' => 'locataire'],
            'ajoute-locataire'     => ['label' => 'Ajouter locataire',      'group' => 'locataire'],
            'Consulter-locataire'  => ['label' => 'Liste locataire',        'group' => 'locataire'],
            'modify-locataire'     => ['label' => 'Modification locataire', 'group' => 'locataire'],
            'delete-locataire'     => ['label' => 'Supprimer locataire',    'group' => 'locataire'],
            'download-recu-avance' => ['label' => 'Télécharger reçu avance','group' => 'locataire'],

            // ── Paiement ──────────────────────────────────────────────────────
            'gestion-paiement'      => ['label' => 'Gestion paiement',          'group' => 'paiement'],
            'ajoute-paiement'       => ['label' => 'Ajouter paiement',          'group' => 'paiement'],
            'Consulter-paiement'    => ['label' => 'Liste paiement',            'group' => 'paiement'],
            'modify-paiement'       => ['label' => 'Modification paiement',     'group' => 'paiement'],
            'delete-paiement'       => ['label' => 'Supprimer paiement',        'group' => 'paiement'],
            'download-recu-location'=> ['label' => 'Télécharger reçu location', 'group' => 'paiement'],

            // ── Statistique ───────────────────────────────────────────────────
            'gestion-statistique'          => ['label' => 'Gestion statistique',          'group' => 'statistique'],
            'proprio-house-chambre-locataire'=> ['label' => 'Menu P/M/C/L',             'group' => 'statistique'],
            'financier'                    => ['label' => 'Menu financier',              'group' => 'statistique'],
            'ancien-recu'                  => ['label' => 'Menu reçu',                  'group' => 'statistique'],
            'gestion-sta-dossier'          => ['label' => 'Gestion des besoins & annonces', 'group' => 'statistique'],

            // ── Dossiers (besoins & annonces) ─────────────────────────────────
            'gestion-dossier'   => ['label' => 'Gestion des besoins & annonces', 'group' => 'dossiers'],
            'dossier-client'    => ['label' => 'Besoins des clients',            'group' => 'dossiers'],
            'dossier-parcelle'  => ['label' => 'Annonces des biens',             'group' => 'dossiers'],
            'ajouter-parcelle'  => ['label' => 'Ajouter annonce',               'group' => 'dossiers'],
            'modifier-parcelle' => ['label' => 'Modifier annonce',              'group' => 'dossiers'],
            'supprimer-parcelle'=> ['label' => 'Supprimer annonce',             'group' => 'dossiers'],
            'cloturer-parcelle' => ['label' => 'Clôturer annonce',              'group' => 'dossiers'],
            'consulter-parcelle'=> ['label' => 'Consulter liste annonce',       'group' => 'dossiers'],
            'ajouter-client'    => ['label' => 'Ajouter besoin',                'group' => 'dossiers'],
            'modifier-client'   => ['label' => 'Modifier besoin',               'group' => 'dossiers'],
            'supprimer-client'  => ['label' => 'Supprimer besoin',              'group' => 'dossiers'],
            'cloturer-client'   => ['label' => 'Clôturer besoin',               'group' => 'dossiers'],
            'consulter-client'  => ['label' => 'Consulter liste besoin',        'group' => 'dossiers'],

            // ── Publicité ─────────────────────────────────────────────────────
            'gestion-publicite'     => ['label' => 'Gestion publicité',   'group' => 'pubs'],
            'ajouter-publicite'     => ['label' => 'Ajouter publicité',   'group' => 'pubs'],
            'modifier-publicite'    => ['label' => 'Modifier publicité',  'group' => 'pubs'],
            'supprimer-publicite'   => ['label' => 'Supprimer publicité', 'group' => 'pubs'],
            'ajouter-image'         => ['label' => 'Ajouter image',       'group' => 'pubs'],
            'voir-detail-publicite' => ['label' => 'Voir détail publicité','group' => 'pubs'],

            // ── Abonnement ────────────────────────────────────────────────────
            'gestion-abonnement' => ['label' => 'Gestion abonnement', 'group' => 'abonnement'],
            'change-abonnement'  => ['label' => 'Changer abonnement', 'group' => 'abonnement'],
            'voir-abonnement'    => ['label' => 'Voir abonnement',    'group' => 'abonnement'],

            // ── Mot de passe ──────────────────────────────────────────────────
            'gestion-mot-passe'    => ['label' => 'Gestion mot de passe',         'group' => 'changePwd'],
            'action-reinitialiser' => ['label' => 'Autoriser la réinitialisation','group' => 'changePwd'],

            // ── Communication (envoi de documents & WhatsApp) ─────────────────
            'envoi-document'    => ['label' => 'Envoyer des documents',           'group' => 'envoi'],
            'historique-envoi'  => ['label' => 'Historique des envois',           'group' => 'envoi'],

            // ── État des lieux ────────────────────────────────────────────────
            'gestion-etat-des-lieux'    => ['label' => 'Gestion états des lieux',      'group' => 'etat_des_lieux'],
            'ajoute-etat-des-lieux'     => ['label' => 'Créer état des lieux',         'group' => 'etat_des_lieux'],
            'Consulter-etat-des-lieux'  => ['label' => 'Consulter états des lieux',    'group' => 'etat_des_lieux'],
            'modify-etat-des-lieux'     => ['label' => 'Modifier état des lieux',      'group' => 'etat_des_lieux'],
            'delete-etat-des-lieux'     => ['label' => 'Supprimer état des lieux',     'group' => 'etat_des_lieux'],
            'download-etat-des-lieux'   => ['label' => 'Télécharger PDF état des lieux','group' => 'etat_des_lieux'],

            // ── Automatisation & Rappels ──────────────────────────────────────
            'gestion-automation'         => ['label' => 'Accès Automatisation & Rappels',  'group' => 'automation'],
            'ajoute-automation'          => ['label' => 'Ajouter une règle',               'group' => 'automation'],
            'toggle-automation'          => ['label' => 'Activer / Désactiver une règle',  'group' => 'automation'],
            'delete-automation'          => ['label' => 'Supprimer une règle',             'group' => 'automation'],
            'voir-historique-automation' => ['label' => 'Voir l\'onglet Historique',       'group' => 'automation'],
            'voir-locataires-automation' => ['label' => 'Voir Locataires non notifiés',    'group' => 'automation'],
            'toggle-optout-automation'   => ['label' => 'Exclure / Inclure un locataire', 'group' => 'automation'],

            // ── Recouvrement ──────────────────────────────────────────────────
            'gestion-recouvrement'   => ['label' => 'Accès Recouvrement',              'group' => 'recouvrement'],
            'ajoute-recouvrement'    => ['label' => 'Ouvrir un dossier',               'group' => 'recouvrement'],
            'Consulter-recouvrement' => ['label' => 'Consulter les dossiers',          'group' => 'recouvrement'],
            'modify-recouvrement'    => ['label' => 'Relancer / Plan apurement',       'group' => 'recouvrement'],
            'delete-recouvrement'    => ['label' => 'Clôturer un dossier',             'group' => 'recouvrement'],
            'download-recouvrement'  => ['label' => 'Télécharger mise en demeure PDF', 'group' => 'recouvrement'],

            // ── Signature électronique ────────────────────────────────────────
            'gestion-signature'  => ['label' => 'Accès Signature électronique',    'group' => 'signature'],
            'ajoute-signature'   => ['label' => 'Créer une demande de signature',  'group' => 'signature'],
            'delete-signature'   => ['label' => 'Annuler une demande',             'group' => 'signature'],
            'download-signature' => ['label' => 'Télécharger le certificat PDF',   'group' => 'signature'],

            // ── Interventions ─────────────────────────────────────────────────
            'gestion-maintenance'   => ['label' => 'Gestion interventions',        'group' => 'maintenance'],
            'ajoute-maintenance'    => ['label' => 'Créer ticket intervention',    'group' => 'maintenance'],
            'Consulter-maintenance' => ['label' => 'Consulter tickets',            'group' => 'maintenance'],
            'modify-maintenance'    => ['label' => 'Modifier / avancer ticket',    'group' => 'maintenance'],
            'delete-maintenance'    => ['label' => 'Supprimer ticket',             'group' => 'maintenance'],
            'gestion-prestataire'   => ['label' => 'Gérer les prestataires',       'group' => 'maintenance'],

        ];

        // ── Insertion idempotente ─────────────────────────────────────────────
        foreach ($allPermissions as $name => $attrs) {
            $perm = Permission::firstOrCreate(
                ['name' => $name],
                ['label' => $attrs['label'], 'group' => $attrs['group']]
            );
            // Mettre à jour le label/group si la permission existait déjà
            $perm->update(['label' => $attrs['label'], 'group' => $attrs['group']]);
        }

        // ── config-paiement : réservé au propriétaire de l'application ────────
        // N'appartient à AUCUN rôle. Assigné uniquement en direct à 
        $configPerm = Permission::firstOrCreate(
            ['name' => 'config-paiement'],
            ['label' => 'Configuration plateforme (Super Admin)', 'group' => 'plateforme']
        );
        $configPerm->update(['label' => 'Configuration plateforme (Super Admin)', 'group' => 'plateforme']);

        $superAdmin = User::where('email', 'alberttchegnon4@gmail.com')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo('config-paiement');
        }
    }
}
