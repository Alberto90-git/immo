<?php

namespace App\Http\Controllers;

use App\Annexe;  
use App\Locataire;  
use Illuminate\Support\Facades\Auth;


class SessionController extends Controller
{
    public function save_session_annexe()
    {
        return Annexe::whereNull('annexes.status')
                                      ->where('annexes.iddirection_ref',Auth::user()->iddirection_ref)
                                      ->get();
    }


    public function save_session_locataire()
    {
        return Locataire::whereNull('locataires.delete_at')
                        ->where('locataires.status',true)
                        ->get();
    }
}


