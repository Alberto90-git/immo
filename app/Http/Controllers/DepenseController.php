<?php

namespace App\Http\Controllers;

use App\Depense;
use App\CategorieDepense;
use App\Proprietaire;
use App\Maison;
use App\Chambre;
use App\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class DepenseController extends Controller
{
    private function dirId(): int
    {
        return (int) Auth::user()->iddirection_ref;
    }

    private function annexeId(): int
    {
        return (int) get_active_annexe_id();
    }

    // ──────────────────────────────────────────────────────────────────────────
    // INDEX
    // ──────────────────────────────────────────────────────────────────────────
    public function index()
    {
        $dirId = $this->dirId();

        $categories = CategorieDepense::where('iddirection_ref', $dirId)
            ->orderBy('nom')
            ->get();

        $depenses = Depense::with(['categorie', 'proprietaire', 'maison', 'chambre'])
            ->where('iddirection_ref', $dirId)
            ->when(!Gate::check('Is_admin'), function ($q) {
                $q->where('idannexe_ref', Auth::user()->idannexe_ref);
            })
            ->orderBy('date_depense', 'desc')
            ->get();

        // Listes pour les selects d'imputation
        $proprietaires = Proprietaire::whereNull('delete_at')
            ->where('iddirection_ref', $dirId)
            ->get(['id', 'nom', 'prenom']);

        $maisons = Maison::whereNull('delete_at')
            ->where('iddirection_ref', $dirId)
            ->get(['id', 'nom_maison']);

        $chambres = Chambre::whereNull('delete_at')
            ->where('iddirection_ref', $dirId)
            ->get(['id', 'numero_chambre', 'maison_id']);

        return view('depenses.index', compact(
            'depenses', 'categories', 'proprietaires', 'maisons', 'chambres'
        ));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // STORE DÉPENSE
    // ──────────────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categorie_id'    => 'required|exists:categories_depenses,id',
            'montant'         => 'required|numeric|min:0',
            'date_depense'    => 'required|date',
            'description'     => 'nullable|string|max:500',
            'type_imputation' => 'required|in:agence,proprietaire,maison,chambre',
            'proprietaire_id' => 'nullable|exists:proprietaires,id',
            'maison_id'       => 'nullable|exists:maisons,id',
            'chambre_id'      => 'nullable|exists:chambres,id',
            'justificatif'    => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        $annexeId = $this->annexeId();
        if (!$annexeId) {
            return response()->json(['status' => false, 'message' => 'Veuillez sélectionner une agence.']);
        }

        $data = [
            'iddirection_ref' => $this->dirId(),
            'idannexe_ref'    => $annexeId,
            'categorie_id'    => $request->categorie_id,
            'montant'         => $request->montant,
            'date_depense'    => $request->date_depense,
            'description'     => $request->description,
            'type_imputation' => $request->type_imputation,
            'proprietaire_id' => $request->proprietaire_id,
            'maison_id'       => $request->maison_id,
            'chambre_id'      => $request->chambre_id,
        ];

        // Upload justificatif
        if ($request->hasFile('justificatif')) {
            $path = $request->file('justificatif')
                ->store('public/depenses/justificatifs');
            $data['justificatif_url'] = str_replace('public/', '', $path);
        }

        try {
            $dep = Depense::create($data);
            $dep->load(['categorie', 'proprietaire', 'maison', 'chambre']);

            activity()->performedOn(new Depense())
                ->causedBy(Auth::user()->id)
                ->log('Ajout dépense : ' . format_price($dep->montant) . ' — ' . ($dep->categorie->nom ?? ''));

            return response()->json([
                'status'  => true,
                'message' => 'Dépense enregistrée avec succès.',
                'data'    => $this->formatDepense($dep),
            ]);
        } catch (QueryException $e) {
            return response()->json(['status' => false, 'message' => 'Erreur lors de l\'enregistrement.']);
        }
    }

    // ──────────────────────────────────────────────────────────────────────────
    // UPDATE DÉPENSE
    // ──────────────────────────────────────────────────────────────────────────
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'              => 'required|exists:depenses,id',
            'categorie_id'    => 'required|exists:categories_depenses,id',
            'montant'         => 'required|numeric|min:0',
            'date_depense'    => 'required|date',
            'description'     => 'nullable|string|max:500',
            'type_imputation' => 'required|in:agence,proprietaire,maison,chambre',
            'proprietaire_id' => 'nullable|exists:proprietaires,id',
            'maison_id'       => 'nullable|exists:maisons,id',
            'chambre_id'      => 'nullable|exists:chambres,id',
            'justificatif'    => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        $dep = Depense::where('id', $request->id)
            ->where('iddirection_ref', $this->dirId())
            ->firstOrFail();

        $data = [
            'categorie_id'    => $request->categorie_id,
            'montant'         => $request->montant,
            'date_depense'    => $request->date_depense,
            'description'     => $request->description,
            'type_imputation' => $request->type_imputation,
            'proprietaire_id' => $request->proprietaire_id,
            'maison_id'       => $request->maison_id,
            'chambre_id'      => $request->chambre_id,
        ];

        if ($request->hasFile('justificatif')) {
            // Supprimer l'ancien fichier
            if ($dep->getRawOriginal('justificatif_url')) {
                Storage::delete('public/' . $dep->getRawOriginal('justificatif_url'));
            }
            $path = $request->file('justificatif')->store('public/depenses/justificatifs');
            $data['justificatif_url'] = str_replace('public/', '', $path);
        }

        $dep->update($data);
        $dep->load(['categorie', 'proprietaire', 'maison', 'chambre']);

        return response()->json([
            'status'  => true,
            'message' => 'Dépense modifiée avec succès.',
            'data'    => $this->formatDepense($dep),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // DESTROY DÉPENSE
    // ──────────────────────────────────────────────────────────────────────────
    public function destroy(Request $request)
    {
        $dep = Depense::where('id', $request->id)
            ->where('iddirection_ref', $this->dirId())
            ->firstOrFail();

        if ($dep->getRawOriginal('justificatif_url')) {
            Storage::delete('public/' . $dep->getRawOriginal('justificatif_url'));
        }

        $dep->delete();

        return response()->json(['status' => true, 'message' => 'Dépense supprimée.']);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // BILAN (AJAX)
    // ──────────────────────────────────────────────────────────────────────────
    public function bilan(Request $request)
    {
        $dirId    = $this->dirId();
        $annee    = $request->input('annee', now()->year);
        $moisDeb  = $request->input('mois_debut', 1);
        $moisFin  = $request->input('mois_fin', 12);
        $dateDebut = Carbon::create($annee, $moisDeb, 1)->startOfMonth();
        $dateFin   = Carbon::create($annee, $moisFin, 1)->endOfMonth();

        // Recettes : somme des paiements sur la période
        $recettesQuery = Facture::where('iddirection_ref', $dirId)
            ->whereNull('delete_at')
            ->whereBetween('date_paiement', [$dateDebut, $dateFin]);

        if (!Gate::check('Is_admin')) {
            $recettesQuery->where('idannexe_ref', Auth::user()->idannexe_ref);
        }
        $recettes = (float) $recettesQuery->sum('montant');

        // Dépenses : somme sur la période
        $depensesQuery = Depense::where('iddirection_ref', $dirId)
            ->whereBetween('date_depense', [$dateDebut, $dateFin]);

        if (!Gate::check('Is_admin')) {
            $depensesQuery->where('idannexe_ref', Auth::user()->idannexe_ref);
        }
        $totalDepenses = (float) $depensesQuery->sum('montant');

        // Dépenses par catégorie
        $parCategorie = Depense::with('categorie')
            ->where('iddirection_ref', $dirId)
            ->whereBetween('date_depense', [$dateDebut, $dateFin])
            ->when(!Gate::check('Is_admin'), fn($q) => $q->where('idannexe_ref', Auth::user()->idannexe_ref))
            ->selectRaw('categorie_id, SUM(montant) as total')
            ->groupBy('categorie_id')
            ->get()
            ->map(fn($r) => [
                'nom'        => $r->categorie->nom ?? __('pages.dep_no_category'),
                'total'      => (float) $r->total,
                'montant_fmt'=> format_price($r->total),
            ]);

        // Synthèse mensuelle pour le graphique
        $mensuel = [];
        for ($m = 1; $m <= 12; $m++) {
            $d = Carbon::create($annee, $m, 1);
            $r = (float) Facture::where('iddirection_ref', $dirId)
                ->whereNull('delete_at')
                ->whereYear('date_paiement', $annee)
                ->whereMonth('date_paiement', $m)
                ->sum('montant');
            $dep = (float) Depense::where('iddirection_ref', $dirId)
                ->whereYear('date_depense', $annee)
                ->whereMonth('date_depense', $m)
                ->sum('montant');
            $mensuel[] = [
                'mois'     => $d->translatedFormat('M'),
                'recettes' => $r,
                'depenses' => $dep,
                'resultat' => $r - $dep,
            ];
        }

        // Synthèse par propriétaire
        $parProprietaire = Depense::with('proprietaire')
            ->where('iddirection_ref', $dirId)
            ->where('type_imputation', 'proprietaire')
            ->whereBetween('date_depense', [$dateDebut, $dateFin])
            ->selectRaw('proprietaire_id, SUM(montant) as total')
            ->groupBy('proprietaire_id')
            ->get()
            ->map(fn($r) => [
                'nom'        => $r->proprietaire ? $r->proprietaire->nom . ' ' . $r->proprietaire->prenom : 'N/A',
                'total'      => (float) $r->total,
                'montant_fmt'=> format_price($r->total),
            ]);

        return response()->json([
            'recettes'         => $recettes,
            'depenses'         => $totalDepenses,
            'resultat'         => $recettes - $totalDepenses,
            'par_categorie'    => $parCategorie,
            'par_proprietaire' => $parProprietaire,
            'mensuel'          => $mensuel,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // EXPORT CSV
    // ──────────────────────────────────────────────────────────────────────────
    public function exportCsv(Request $request)
    {
        $dirId = $this->dirId();

        $query = Depense::with(['categorie', 'proprietaire', 'maison', 'chambre'])
            ->where('iddirection_ref', $dirId)
            ->when(!Gate::check('Is_admin'), fn($q) => $q->where('idannexe_ref', Auth::user()->idannexe_ref));

        if ($request->filled('annee')) {
            $query->whereYear('date_depense', $request->annee);
        }
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        $depenses = $query->orderBy('date_depense', 'desc')->get();

        $filename = 'depenses_' . now()->format('Ymd_His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($depenses) {
            $file = fopen('php://output', 'w');
            // BOM UTF-8 pour Excel
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Date', 'Catégorie', 'Type catégorie', 'Montant (' . get_devise_courante() . ')', 'Imputation', 'Détail imputation', 'Description', 'Justificatif'], ';');
            foreach ($depenses as $d) {
                $imputation = match ($d->type_imputation) {
                    'proprietaire' => $d->proprietaire ? $d->proprietaire->nom . ' ' . $d->proprietaire->prenom : '',
                    'maison'       => $d->maison ? $d->maison->nom_maison : '',
                    'chambre'      => $d->chambre ? 'Chambre n° ' . $d->chambre->numero_chambre : '',
                    default        => 'Agence',
                };
                fputcsv($file, [
                    $d->date_depense->format('d/m/Y'),
                    $d->categorie->nom ?? '',
                    $d->categorie->type_label ?? '',
                    number_format($d->montant, 2, ',', ' '),
                    $d->imputation_label,
                    $imputation,
                    $d->description ?? '',
                    $d->getRawOriginal('justificatif_url') ? 'Oui' : 'Non',
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // CATÉGORIES CRUD
    // ──────────────────────────────────────────────────────────────────────────
    public function storeCategorie(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom'  => 'required|string|max:100',
            'type' => 'required|in:entretien,taxes,honoraires,travaux,assurance,autre',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        $cat = CategorieDepense::create([
            'iddirection_ref' => $this->dirId(),
            'nom'  => $request->nom,
            'type' => $request->type,
        ]);

        return response()->json(['status' => true, 'message' => 'Catégorie créée.', 'data' => $cat]);
    }

    public function updateCategorie(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'   => 'required|exists:categories_depenses,id',
            'nom'  => 'required|string|max:100',
            'type' => 'required|in:entretien,taxes,honoraires,travaux,assurance,autre',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        $cat = CategorieDepense::where('id', $request->id)
            ->where('iddirection_ref', $this->dirId())
            ->firstOrFail();
        $cat->update(['nom' => $request->nom, 'type' => $request->type]);

        return response()->json(['status' => true, 'message' => 'Catégorie modifiée.', 'data' => $cat]);
    }

    public function destroyCategorie(Request $request)
    {
        $cat = CategorieDepense::where('id', $request->id)
            ->where('iddirection_ref', $this->dirId())
            ->firstOrFail();

        if ($cat->depenses()->count()) {
            return response()->json(['status' => false, 'message' => 'Impossible : des dépenses utilisent cette catégorie.']);
        }

        $cat->delete();
        return response()->json(['status' => true, 'message' => 'Catégorie supprimée.']);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // SYNTHÈSE ANNUELLE REVENUS FONCIERS PAR PROPRIÉTAIRE (CSV)
    // ──────────────────────────────────────────────────────────────────────────
    public function syntheseProprietaire(Request $request)
    {
        $dirId = $this->dirId();
        $annee = (int) $request->input('annee', now()->year);

        $dateDebut = Carbon::create($annee, 1, 1)->startOfDay();
        $dateFin   = Carbon::create($annee, 12, 31)->endOfDay();

        // Recettes par propriétaire via maisons
        $recettesParProprio = \Illuminate\Support\Facades\DB::table('factures')
            ->join('maisons', 'factures.maison_id', '=', 'maisons.id')
            ->join('proprietaires', 'maisons.proprio_id', '=', 'proprietaires.id')
            ->where('factures.iddirection_ref', $dirId)
            ->whereNull('factures.delete_at')
            ->whereBetween('factures.date_paiement', [$dateDebut, $dateFin])
            ->selectRaw('proprietaires.id, proprietaires.nom, proprietaires.prenom, SUM(factures.montant) as total_recettes, COUNT(DISTINCT maisons.id) as nb_maisons')
            ->groupBy('proprietaires.id', 'proprietaires.nom', 'proprietaires.prenom')
            ->get()
            ->keyBy('id');

        // Dépenses imputées par propriétaire
        $depensesParProprio = Depense::where('iddirection_ref', $dirId)
            ->where('type_imputation', 'proprietaire')
            ->whereBetween('date_depense', [$dateDebut, $dateFin])
            ->selectRaw('proprietaire_id, SUM(montant) as total_depenses')
            ->groupBy('proprietaire_id')
            ->pluck('total_depenses', 'proprietaire_id');

        // Fusion des données
        $lignes = collect($recettesParProprio->all())
            ->map(function ($row) use ($depensesParProprio) {
                $dep = (float) ($depensesParProprio[$row->id] ?? 0);
                return [
                    'proprio'        => $row->nom . ' ' . $row->prenom,
                    'nb_maisons'     => $row->nb_maisons,
                    'recettes'       => (float) $row->total_recettes,
                    'depenses'       => $dep,
                    'resultat_net'   => (float) $row->total_recettes - $dep,
                ];
            })
            ->sortByDesc('recettes')
            ->values();

        $devise   = get_devise_courante($dirId);
        $filename = 'synthese_revenus_fonciers_' . $annee . '_' . now()->format('Ymd') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($lignes, $annee, $devise) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Synthèse annuelle des revenus fonciers — ' . $annee], ';');
            fputcsv($file, ['Propriétaire', 'Nbre maisons', 'Recettes (' . $devise . ')', 'Dépenses imputées (' . $devise . ')', 'Résultat net (' . $devise . ')'], ';');
            foreach ($lignes as $l) {
                fputcsv($file, [
                    $l['proprio'],
                    $l['nb_maisons'],
                    number_format($l['recettes'],     2, ',', ' '),
                    number_format($l['depenses'],     2, ',', ' '),
                    number_format($l['resultat_net'], 2, ',', ' '),
                ], ';');
            }
            // Ligne totaux
            $totRec = $lignes->sum('recettes');
            $totDep = $lignes->sum('depenses');
            fputcsv($file, ['TOTAL', '', number_format($totRec, 2, ',', ' '), number_format($totDep, 2, ',', ' '), number_format($totRec - $totDep, 2, ',', ' ')], ';');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helper format
    // ──────────────────────────────────────────────────────────────────────────
    private function formatDepense(Depense $d): array
    {
        $impDetail = match ($d->type_imputation) {
            'proprietaire' => $d->proprietaire ? $d->proprietaire->nom . ' ' . $d->proprietaire->prenom : '',
            'maison'       => $d->maison ? $d->maison->nom_maison : '',
            'chambre'      => $d->chambre ? 'Chambre n° ' . $d->chambre->numero_chambre : '',
            default        => 'Agence',
        };

        return [
            'id'               => $d->id,
            'date'             => $d->date_depense->format('d/m/Y'),
            'date_raw'         => $d->date_depense->format('Y-m-d'),
            'categorie_id'     => $d->categorie_id,
            'categorie_nom'    => $d->categorie->nom ?? 'Sans catégorie',
            'categorie_type'   => $d->categorie->type ?? '',
            'montant'          => $d->montant,
            'montant_fmt'      => format_price($d->montant),
            'description'      => $d->description ?? '',
            'type_imputation'  => $d->type_imputation,
            'imputation_label' => $d->imputation_label,
            'imp_detail'       => $impDetail,
            'proprietaire_id'  => $d->proprietaire_id,
            'maison_id'        => $d->maison_id,
            'chambre_id'       => $d->chambre_id,
            'justificatif_url' => $d->getOriginal('justificatif_url')
                ? asset('storage/' . $d->getOriginal('justificatif_url'))
                : null,
            'justificatif_raw' => $d->getOriginal('justificatif_url'),
        ];
    }
}
