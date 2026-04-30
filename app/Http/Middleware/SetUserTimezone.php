<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Parametre;
use Illuminate\Support\Facades\Auth;

class SetUserTimezone
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $directionId = Auth::user()->iddirection_ref;
            $parametre   = Parametre::where('iddirection_ref', $directionId)->first();
            $timezone    = $parametre?->timezone ?? config('app.timezone');

            config(['app.timezone' => $timezone]);
            date_default_timezone_set($timezone);
            Carbon::setLocale('fr');
        }

        return $next($request);
    }
}
