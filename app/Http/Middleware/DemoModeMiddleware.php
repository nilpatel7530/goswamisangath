<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DemoModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $demoMode = SiteSetting::get('demo_mode', 'off');

        if ($demoMode === 'on') {
            // Allow Admins always
            if (Auth::check() && Auth::user()->role === 'admin') {
                return $next($request);
            }

            // Define allowed routes during demo mode
            $allowedRoutes = [
                'home',
                'welcome',
                'login',
                'login.store',
                'admin.login',
                'admin.login.store',
                'register',
                'register.post',
                'signup',
                'signup.store',
                'google.login',
                'google.callback',
                'logout',
                'profile.complete',
                'profile.complete.store',
                'profile.verify-id',
                'profile.verify-id.store',
                'thank-you', // The countdown page itself
                'set-locale',
                'getCountries',
                'getStates',
                'getCities',
                'get-educations',
                'terms',
                'privacy',
                'about',
                'success-stories',
                'membership',
                'language.switch',
                'language.switch.ajax',
                'translations.get',
            ];

            $currentRouteName = $request->route() ? $request->route()->getName() : null;
            Log::info('DemoModeMiddleware: currentRouteName = ' . ($currentRouteName ?? 'null') . ', path = ' . $request->path());

            // If it's an allowed route or an admin route, let it pass
            if (in_array($currentRouteName, $allowedRoutes) || $request->is('admin*')) {
                return $next($request);
            }
            
            // Special check for dynamic routes like get-educations/{id}
            if (str_contains($request->path(), 'get-educations')) {
                return $next($request);
            }

            // If user is logged in but not an admin, and trying to access restricted page
            if (Auth::check()) {
                // If they have pending verification or just registered, they should see "Thank You"
                // But we want to block access to Dashboard, Matches, etc.
                return redirect()->route('thank-you');
            }
            
            // For unauthenticated guests trying to access restricted pages, redirect to signup
            return redirect()->route('signup');
        }

        return $next($request);
    }
}
