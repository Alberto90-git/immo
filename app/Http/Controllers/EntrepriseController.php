<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Annexe;
use Carbon\Carbon;

class EntrepriseController extends Controller
{
    public function display_compte_entreprise()
    {
        $data = User::join('directions', 'users.iddirection_ref', '=', 'directions.iddirection')
                     ->whereNull('users.status')
                     //->whereNull('blocage_entreprise')
                     ->where('is_admin',true)
                     ->get();

        $dataannexe = Annexe::where('designation','!=','All Digital Agency')
                            ->get();

        //dd($dataannexe);

        return view('entreprise.liste_compte', compact(['data','data','dataannexe','dataannexe']));
    }

    public function manage_compte($ids){
        $checkstatut = User::Where('iddirection_ref', $ids)->get();

        //dd($checkstatut->first()->iddirection_ref);

        if (empty($checkstatut->first()?->blocage_entreprise)) {

            for($debut = 0; $debut < count($checkstatut); $debut++){
                User::where('iddirection_ref',$checkstatut->first()->iddirection_ref)
                    ->update(['blocage_entreprise' => Carbon::now()]);
            }
            

            Annexe::where('iddirection_ref',$ids)
                    ->update(['blocage_annexe' => Carbon::now()]);

            return redirect()->back()->with('success', 'Bloqué avec succès ');
        } else {

            for($debut = 0; $debut < count($checkstatut); $debut++){
                User::where('iddirection_ref',$checkstatut->first()->iddirection_ref)
                    ->update(['blocage_entreprise' => null]);
            }

            Annexe::where('iddirection_ref',$ids)
                    ->update(['blocage_annexe' => null]);

            return redirect()->back()->with('success', 'Débloqué avec succès ');
            
        }
    }
}
