<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = session('locale')
               ?? $request->cookie('lokativ_locale')
               ?? config('app.locale', 'fr');
        if (!in_array($locale, ['fr', 'en'])) {
            $locale = 'fr';
        }
        App::setLocale($locale);
        return $next($request);
    }
}
