<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class GetBrowserLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $asTranslation = ['en','de'];
        $userLocales = $request->getLanguages();
        $locale = Str::before($request->getPreferredLanguage($userLocales),'_');

        //check if the locale is in the array of available locales
        if (!in_array($locale, $asTranslation)) {
            $locale = 'en';
        }

        //remove the brackets from the locale
        app()->setLocale( Str::before($locale, '_'));

        return $next($request);
    }
}
