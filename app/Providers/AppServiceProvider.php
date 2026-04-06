<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View Composer for sharing status across all views
        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('site_settings')) {
                    $settings = DB::table('site_settings')->pluck('value', 'key');
                    $view->with('siteSettings', (object) $settings->toArray());
                }
            } catch (\Exception $e) {
                // Silently fail if table doesn't exist yet
            }
        });

        // View Composer for sharing membership status
        View::composer('*', function ($view) {
            if (Auth::check()) {
                try {
                    $user = Auth::user();
                    $membership = DB::table('user_memberships')
                        ->join('memberships', 'user_memberships.membership_id', '=', 'memberships.id')
                        ->where('user_memberships.user_id', $user->id)
                        ->where('user_memberships.is_active', 1)
                        ->select('memberships.name', 'memberships.visits_allowed', 'user_memberships.visits_used')
                        ->first();
                    $view->with('membership', $membership);
                } catch (\Exception $e) {
                    // Silently fail
                }
            }
        });

        // Set locale from session
        View::composer('*', function ($view) {
            $locale = Session::get('locale', config('app.locale', 'en'));
            App::setLocale($locale);
        });
    }
}
