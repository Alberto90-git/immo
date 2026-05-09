<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPerformanceIndexesToCoreTables extends Migration
{
    private function idx(string $table, array $cols, string $name): void
    {
        DB::statement("CREATE INDEX IF NOT EXISTS {$name} ON {$table} (" . implode(', ', $cols) . ")");
    }

    private function idxPartial(string $table, array $cols, string $name, string $where): void
    {
        DB::statement("CREATE INDEX IF NOT EXISTS {$name} ON {$table} (" . implode(', ', $cols) . ") WHERE {$where}");
    }

    public function up(): void
    {
        // ── FACTURES — colonnes critiques pour le dashboard ─────────────────────
        // date_paiement : filtrée dans 80%+ des requêtes dashboard/stats
        $this->idx('factures', ['date_paiement'],                               'idx_factures_date_paiement');
        // Composite (direction, date_paiement) : couvre toutes les requêtes dashboard
        $this->idx('factures', ['iddirection_ref', 'date_paiement'],            'idx_factures_dir_date');
        // Composite (direction, annexe, date_paiement) : pour requêtes multi-agences
        $this->idx('factures', ['iddirection_ref', 'idannexe_ref', 'date_paiement'], 'idx_factures_dir_ann_date');
        // locataire_id + date_paiement : dernier paiement par locataire
        $this->idx('factures', ['locataire_id', 'date_paiement'],               'idx_factures_loc_date');
        // delete_at NULL partiel (les 95%+ des lignes actives)
        $this->idxPartial('factures', ['iddirection_ref', 'date_paiement'],
            'idx_factures_dir_date_active', 'delete_at IS NULL');

        // ── LOCATAIRES — colonnes critiques ─────────────────────────────────────
        // date_entree : GROUP BY month/year pour stats 12 mois
        $this->idx('locataires', ['date_entree'],                               'idx_locataires_date_entree');
        // Composite (direction, status) : filtre très fréquent
        $this->idx('locataires', ['iddirection_ref', 'status'],                 'idx_locataires_dir_status');
        // Composite (direction, annexe, status) + index partiel actifs seulement
        $this->idxPartial('locataires', ['iddirection_ref', 'idannexe_ref'],
            'idx_locataires_dir_ann_active', 'status = true AND delete_at IS NULL');
        // date_entree pour GROUP BY annuel/mensuel
        $this->idxPartial('locataires', ['iddirection_ref', 'date_entree'],
            'idx_locataires_dir_date_entree', 'status = true AND delete_at IS NULL');

        // ── CHAMBRES ─────────────────────────────────────────────────────────────
        // etat (0/1 libre/occupée) : utilisé dans tous les filtres disponibilités
        $this->idx('chambres', ['etat'],                                        'idx_chambres_etat');
        // Composite (direction, etat) : requête disponibilités
        $this->idx('chambres', ['iddirection_ref', 'etat'],                     'idx_chambres_dir_etat');
        // Index partiel chambres libres uniquement
        $this->idxPartial('chambres', ['iddirection_ref', 'maison_id'],
            'idx_chambres_libres', 'etat = false AND delete_at IS NULL');

        // ── PRÉ-RÉSERVATIONS — pour le filtre chambres disponibles ─────────────
        $this->idxPartial('pre_reservations', ['chambre_id'],
            'idx_prereserv_chambre_active', 'statut = \'active\'');
        $this->idxPartial('pre_reservations', ['iddirection_ref'],
            'idx_prereserv_dir_active', 'statut = \'active\'');

        // ── VISITES PROSPECTS ────────────────────────────────────────────────────
        $this->idx('visites_prospects', ['date_visite'],                        'idx_visites_date');
        $this->idx('visites_prospects', ['prospect_id'],                        'idx_visites_prospect');

        // ── ACTIVITY_LOG — pour l'historique filtré par date ────────────────────
        $this->idx('activity_log', ['causer_id', 'created_at'],                'idx_activity_causer_date');

        // ── DOSSIERS RECOUVREMENT ────────────────────────────────────────────────
        $this->idxPartial('recouvrement_dossiers', ['iddirection_ref'],
            'idx_recouvr_dir_actif', "statut NOT IN ('resolu', 'classe')");

        // ── DEPENSES ─────────────────────────────────────────────────────────────
        $this->idx('depenses', ['iddirection_ref', 'date_depense'],            'idx_depenses_dir_date');
    }

    public function down(): void
    {
        $indexes = [
            'idx_factures_date_paiement', 'idx_factures_dir_date', 'idx_factures_dir_ann_date',
            'idx_factures_loc_date', 'idx_factures_dir_date_active',
            'idx_locataires_date_entree', 'idx_locataires_dir_status',
            'idx_locataires_dir_ann_active', 'idx_locataires_dir_date_entree',
            'idx_chambres_etat', 'idx_chambres_dir_etat', 'idx_chambres_libres',
            'idx_prereserv_chambre_active', 'idx_prereserv_dir_active',
            'idx_visites_date', 'idx_visites_prospect',
            'idx_activity_causer_date', 'idx_recouvr_dir_actif', 'idx_depenses_dir_date',
        ];
        foreach ($indexes as $idx) {
            try { DB::statement("DROP INDEX IF EXISTS {$idx}"); } catch (\Exception $e) {}
        }
    }
}
