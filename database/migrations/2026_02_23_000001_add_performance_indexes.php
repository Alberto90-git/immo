<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPerformanceIndexes extends Migration
{
    private function createIndexIfNotExists(string $table, array $columns, string $indexName): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("CREATE INDEX IF NOT EXISTS {$indexName} ON {$table} (" . implode(', ', $columns) . ")");
        } else {
            // MySQL / SQLite : vérification manuelle
            $exists = collect(DB::select("SHOW INDEX FROM {$table} WHERE Key_name = '{$indexName}'"))->isNotEmpty();
            if (!$exists) {
                DB::statement("CREATE INDEX {$indexName} ON {$table} (" . implode(', ', $columns) . ")");
            }
        }
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        $driver = DB::getDriverName();

        try {
            if ($driver === 'pgsql') {
                DB::statement("DROP INDEX IF EXISTS {$indexName}");
            } else {
                DB::statement("DROP INDEX IF EXISTS {$indexName} ON {$table}");
            }
        } catch (\Exception $e) {
            // Index inexistant, on ignore
        }
    }

    public function up()
    {
        // ── locataires ──────────────────────────────────────────────
        $this->createIndexIfNotExists('locataires', ['iddirection_ref'], 'idx_locataires_direction');
        $this->createIndexIfNotExists('locataires', ['idannexe_ref'],    'idx_locataires_annexe');
        $this->createIndexIfNotExists('locataires', ['maison_id'],       'idx_locataires_maison');
        $this->createIndexIfNotExists('locataires', ['chambre_id'],      'idx_locataires_chambre');
        $this->createIndexIfNotExists('locataires', ['status'],          'idx_locataires_status');
        $this->createIndexIfNotExists('locataires', ['delete_at'],       'idx_locataires_delete_at');

        // ── factures ─────────────────────────────────────────────────
        $this->createIndexIfNotExists('factures', ['iddirection_ref'], 'idx_factures_direction');
        $this->createIndexIfNotExists('factures', ['idannexe_ref'],    'idx_factures_annexe');
        $this->createIndexIfNotExists('factures', ['maison_id'],       'idx_factures_maison');
        $this->createIndexIfNotExists('factures', ['chambre_id'],      'idx_factures_chambre');
        $this->createIndexIfNotExists('factures', ['locataire_id'],    'idx_factures_locataire');
        $this->createIndexIfNotExists('factures', ['delete_at'],       'idx_factures_delete_at');

        // ── maisons ───────────────────────────────────────────────────
        $this->createIndexIfNotExists('maisons', ['iddirection_ref'], 'idx_maisons_direction');
        $this->createIndexIfNotExists('maisons', ['idannexe_ref'],    'idx_maisons_annexe');
        $this->createIndexIfNotExists('maisons', ['proprio_id'],      'idx_maisons_proprio');
        $this->createIndexIfNotExists('maisons', ['delete_at'],       'idx_maisons_delete_at');

        // ── proprietaires ─────────────────────────────────────────────
        $this->createIndexIfNotExists('proprietaires', ['iddirection_ref'], 'idx_proprietaires_direction');
        $this->createIndexIfNotExists('proprietaires', ['idannexe_ref'],    'idx_proprietaires_annexe');
        $this->createIndexIfNotExists('proprietaires', ['delete_at'],       'idx_proprietaires_delete_at');

        // ── chambres ──────────────────────────────────────────────────
        $this->createIndexIfNotExists('chambres', ['iddirection_ref'], 'idx_chambres_direction');
        $this->createIndexIfNotExists('chambres', ['idannexe_ref'],    'idx_chambres_annexe');
        $this->createIndexIfNotExists('chambres', ['maison_id'],       'idx_chambres_maison');
        $this->createIndexIfNotExists('chambres', ['delete_at'],       'idx_chambres_delete_at');

        // ── users ─────────────────────────────────────────────────────
        $this->createIndexIfNotExists('users', ['iddirection_ref'], 'idx_users_direction');
        $this->createIndexIfNotExists('users', ['idannexe_ref'],    'idx_users_annexe');
        $this->createIndexIfNotExists('users', ['status'],          'idx_users_status');

        // ── annexes ───────────────────────────────────────────────────
        $this->createIndexIfNotExists('annexes', ['iddirection_ref'], 'idx_annexes_direction');
        $this->createIndexIfNotExists('annexes', ['status'],          'idx_annexes_status');
        $this->createIndexIfNotExists('annexes', ['blocage_annexe'],  'idx_annexes_blocage');

        // ── prixes ────────────────────────────────────────────────────
        $this->createIndexIfNotExists('prixes', ['chambre_id'], 'idx_prixes_chambre');
        $this->createIndexIfNotExists('prixes', ['maison_id'],  'idx_prixes_maison');
        $this->createIndexIfNotExists('prixes', ['delete_at'],  'idx_prixes_delete_at');

        // ── activity_log ──────────────────────────────────────────────
        $this->createIndexIfNotExists('activity_log', ['causer_id'],   'idx_activity_log_causer');
        $this->createIndexIfNotExists('activity_log', ['created_at'],  'idx_activity_log_created_at');
    }

    public function down()
    {
        $indexes = [
            'locataires'   => ['idx_locataires_direction', 'idx_locataires_annexe', 'idx_locataires_maison', 'idx_locataires_chambre', 'idx_locataires_status', 'idx_locataires_delete_at'],
            'factures'     => ['idx_factures_direction', 'idx_factures_annexe', 'idx_factures_maison', 'idx_factures_chambre', 'idx_factures_locataire', 'idx_factures_delete_at'],
            'maisons'      => ['idx_maisons_direction', 'idx_maisons_annexe', 'idx_maisons_proprio', 'idx_maisons_delete_at'],
            'proprietaires'=> ['idx_proprietaires_direction', 'idx_proprietaires_annexe', 'idx_proprietaires_delete_at'],
            'chambres'     => ['idx_chambres_direction', 'idx_chambres_annexe', 'idx_chambres_maison', 'idx_chambres_delete_at'],
            'users'        => ['idx_users_direction', 'idx_users_annexe', 'idx_users_status'],
            'annexes'      => ['idx_annexes_direction', 'idx_annexes_status', 'idx_annexes_blocage'],
            'prixes'       => ['idx_prixes_chambre', 'idx_prixes_maison', 'idx_prixes_delete_at'],
            'activity_log' => ['idx_activity_log_causer', 'idx_activity_log_created_at'],
        ];

        foreach ($indexes as $table => $names) {
            foreach ($names as $name) {
                $this->dropIndexIfExists($table, $name);
            }
        }
    }
}
