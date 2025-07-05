<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Direction;
use App\Annexe;  
use App\Locataire;  
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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


