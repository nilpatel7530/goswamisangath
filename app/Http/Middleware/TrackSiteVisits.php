<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // We only track GET requests that are not AJAX to avoid counting asset loads.
        if ($request->isMethod('get') && !$request->ajax()) {
            DB::table('site_visits')
                ->updateOrInsert(
                    ['visit_date' => today()],
                    ['visits_count' => DB::raw('visits_count + 1')]
                );
        }

        return $next($request);
    }
}
