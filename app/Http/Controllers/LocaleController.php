<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(Request $request, $locale)
    {
        if (!in_array($locale, ['fr', 'en'])) {
            $locale = 'fr';
        }
        session(['locale' => $locale]);
        return redirect()->back()
            ->cookie('lokativ_locale', $locale, 60 * 24 * 365, '/');
    }
}
