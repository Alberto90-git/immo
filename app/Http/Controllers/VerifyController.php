<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class VerifyController extends Controller
{
    public function VerifyEmail()
    {

        if ($token == null) {
            session()->flash('message', 'Try to connect is invalid');
            return redirect()->route('login');
        }

        $agent = User::where('email_verification_token', $token)->first();

        if ($agent == null) {
            session()->flash('message', 'Try to connect is invalid');
            return redirect()->route('login2');
        }


        User::where('id', $agent->id)
            ->update([
                'email_verified_at' => Carbon::now(),
                'email_verification_token' => NULL,
            ]);

        session()->flash('message', 'Your account is enable, you can logged in now.');

        return redirect()->route('connexion');


    }

   

}