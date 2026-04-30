<?php

namespace App\Http\Controllers;

use App\Parametre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocaleController extends Controller
{
    public function switch(Request $request, $locale)
    {
        if (!in_array($locale, ['fr', 'en'])) {
            $locale = 'fr';
        }

        session(['locale' => $locale]);

        // Persister en base pour les envois hors-session (cron, queue)
        if (Auth::check() && Auth::user()->iddirection_ref) {
            Parametre::where('iddirection_ref', Auth::user()->iddirection_ref)
                ->update(['locale' => $locale]);
        }

        return redirect()->back()
            ->cookie('lokativ_locale', $locale, 60 * 24 * 365, '/');
    }
}
