<?php

namespace App\Http\Middleware;

use App\Settings\DeveloperSettings;
use App\Settings\GeneralSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyAppSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $developer = app(DeveloperSettings::class);

        if ($developer->maintenance_mode && ! $request->user()?->isAdmin()) {
            return response()->view('errors.maintenance', [], 503);
        }

        $general = app(GeneralSettings::class);
        date_default_timezone_set($general->timezone);

        return $next($request);
    }
}
