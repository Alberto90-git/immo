<?php

namespace App\Http\Controllers;

use App\User;
use App\Annexe;
use App\Direction;
use Carbon\Carbon;

class EntrepriseController extends Controller
{
    public function display_compte_entreprise()
    {
        $data = User::join('directions', 'users.iddirection_ref', '=', 'directions.iddirection')
                     ->leftJoin('plans', 'directions.idplan_ref', '=', 'plans.idplan')
                     ->whereNull('users.status')
                     ->where('is_admin',true)
                     ->select(
                         'users.*',
                         'directions.designation',
                         'directions.iddirection',
                         'directions.statut_abonnement',
                         'directions.abonnement_debut',
                         'directions.abonnement_fin',
                         'plans.nom as plan_nom',
                         'plans.prix_annuel as plan_prix'
                     )
                     ->get();

        $dataannexe = Annexe::where('designation','!=','All Digital Agency')
                            ->get();

        return view('entreprise.liste_compte', compact(['data','data','dataannexe','dataannexe']));
    }

    public function manage_compte($ids){
        $checkstatut = User::Where('iddirection_ref', $ids)->get();

        //dd($checkstatut->first()->iddirection_ref);

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

            // Activer l'abonnement lors de la validation
            Direction::where('iddirection', $ids)
                ->update(['statut_abonnement' => 'actif']);

            return redirect()->back()->with('success', 'Abonnement validé et compte débloqué avec succès');

        }
    }
}
