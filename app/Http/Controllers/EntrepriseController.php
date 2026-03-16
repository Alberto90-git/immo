<?php

namespace App\Http\Controllers;

use App\User;
use App\Annexe;
use App\Direction;
use App\Plan;
use App\Maison;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EntrepriseController extends Controller
{
    public function display_compte_entreprise()
    {
        $data = User::join('directions', 'users.iddirection_ref', '=', 'directions.iddirection')
                     ->leftJoin('plans', 'directions.idplan_ref', '=', 'plans.idplan')
                     ->whereNull('users.status')
                     ->where('is_admin', true)
                     ->select(
                         'users.*',
                         'directions.designation',
                         'directions.iddirection',
                         'directions.telephone',
                         'directions.email as direction_email',
                         'directions.siege_social',
                         'directions.statut_abonnement',
                         'directions.abonnement_debut',
                         'directions.abonnement_fin',
                         'plans.idplan',
                         'plans.nom as plan_nom',
                         'plans.prix_annuel as plan_prix',
                         'plans.max_maisons',
                         'plans.max_annexes'
                     )
                     ->get()
                     ->map(function ($item) {
                         $item->nb_maisons   = Maison::where('iddirection_ref', $item->iddirection)->count();
                         $item->nb_agences   = max(0, Annexe::where('iddirection_ref', $item->iddirection)->count() - 1);
                         $item->jours_restants = $item->abonnement_fin
                             ? Carbon::today()->diffInDays(Carbon::parse($item->abonnement_fin), false)
                             : null;
                         return $item;
                     });

        $dataannexe = Annexe::where('designation', '!=', 'All Digital Agency')->get();

        $plans = Plan::where('is_active', true)->whereNull('delete_at')->get();

        // Statistiques résumées
        $stats = [
            'total'      => $data->count(),
            'actifs'     => $data->where('blocage_entreprise', null)->count(),
            'en_attente' => $data->where('blocage_entreprise', '!=', null)->count(),
            'expires'    => $data->filter(fn($d) => $d->abonnement_fin && Carbon::parse($d->abonnement_fin)->isPast())->count(),
        ];

        return view('entreprise.liste_compte', compact('data', 'dataannexe', 'plans', 'stats'));
    }

    public function manage_compte($ids)
    {
        $checkstatut = User::Where('iddirection_ref', $ids)->get();

        if (empty($checkstatut->first()?->blocage_entreprise)) {
            User::where('iddirection_ref', $ids)
                ->update(['blocage_entreprise' => Carbon::now()]);
            Annexe::where('iddirection_ref', $ids)
                    ->update(['blocage_annexe' => Carbon::now()]);

            return redirect()->back()->with('success', 'Bloqué avec succès');
        } else {
            User::where('iddirection_ref', $ids)
                ->update(['blocage_entreprise' => null]);
            Annexe::where('iddirection_ref', $ids)
                    ->update(['blocage_annexe' => null]);
            Direction::where('iddirection', $ids)
                ->update(['statut_abonnement' => 'actif']);

            return redirect()->back()->with('success', 'Abonnement validé et compte débloqué avec succès');
        }
    }

    public function changePlanAdmin(Request $request)
    {
        $request->validate([
            'iddirection' => 'required|integer',
            'idplan'      => 'required|integer',
            'duree'       => 'required|integer|min:1|max:60',
        ]);

        $direction = Direction::find($request->iddirection);
        if (!$direction) {
            return response()->json(['status' => false, 'message' => 'Entreprise introuvable']);
        }

        $plan = Plan::find($request->idplan);
        if (!$plan) {
            return response()->json(['status' => false, 'message' => 'Plan introuvable']);
        }

        $debut = Carbon::now();
        $fin   = Carbon::now()->addMonths($request->duree);

        $direction->update([
            'idplan_ref'         => $plan->idplan,
            'abonnement_debut'   => $debut,
            'abonnement_fin'     => $fin,
            'statut_abonnement'  => 'actif',
        ]);

        User::where('iddirection_ref', $direction->iddirection)
            ->update(['blocage_entreprise' => null]);
        Annexe::where('iddirection_ref', $direction->iddirection)
              ->update(['blocage_annexe' => null]);

        return response()->json([
            'status'  => true,
            'message' => "Plan changé vers « {$plan->nom} » pour {$request->duree} mois. Compte activé.",
        ]);
    }

    public function renouvelerAbonnement(Request $request)
    {
        $request->validate([
            'iddirection' => 'required|integer',
            'duree'       => 'required|integer|min:1|max:60',
        ]);

        $direction = Direction::find($request->iddirection);
        if (!$direction) {
            return response()->json(['status' => false, 'message' => 'Entreprise introuvable']);
        }

        $base = ($direction->abonnement_fin && Carbon::parse($direction->abonnement_fin)->isFuture())
            ? Carbon::parse($direction->abonnement_fin)
            : Carbon::now();

        $direction->update([
            'abonnement_fin'    => $base->addMonths($request->duree),
            'statut_abonnement' => 'actif',
        ]);

        User::where('iddirection_ref', $direction->iddirection)
            ->update(['blocage_entreprise' => null]);
        Annexe::where('iddirection_ref', $direction->iddirection)
              ->update(['blocage_annexe' => null]);

        return response()->json([
            'status'  => true,
            'message' => "Abonnement renouvelé de {$request->duree} mois.",
        ]);
    }
}
