<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if a 'locale' value has been stored in the session.
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            Log::info('Locale found in session: ' . $locale);
            
            // Verify that the stored locale is one of the languages your application supports.
            if (in_array($locale, ['en', 'hi', 'gu'])) {
                // Set the application's language for the current request.
                App::setLocale($locale);
                Log::info('Locale set to: ' . $locale);
            } else {
                // If invalid locale, default to English
                App::setLocale('en');
            }
        } else {
            // If no locale in session, default to English
            App::setLocale('en');
            Session::put('locale', 'en');
        }

        return $next($request);
    }
}