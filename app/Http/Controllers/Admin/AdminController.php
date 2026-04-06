<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Existing stats
        $totalUsers = User::count();
        $newUsersToday = User::whereDate('created_at', today())->count();

        // Data for Visits Line Chart (last 7 days)
        $visitsData = DB::table('site_visits')
            ->where('visit_date', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->orderBy('visit_date', 'asc')
            ->pluck('visits_count', 'visit_date');
            
        $visitLabels = [];
        $visitCounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $visitLabels[] = $date->format('M d');
            $visitCounts[] = $visitsData[$date->format('Y-m-d')] ?? 0;
        }

        // Data for Subscribers Pie Chart
        $subscribedCounts = DB::table('memberships')
            ->leftJoin('user_memberships', function ($join) {
                $join->on('memberships.id', '=', 'user_memberships.membership_id')
                     ->where('user_memberships.is_active', 1);
            })
            ->groupBy('memberships.name')
            ->select('memberships.name', DB::raw('count(user_memberships.user_id) as count'))
            ->orderBy('memberships.price', 'desc') // Order by price to get Gold, Silver, Bronze
            ->get();

        $totalSubscribed = $subscribedCounts->sum('count');
        $nonSubscribedCount = $totalUsers > $totalSubscribed ? $totalUsers - $totalSubscribed : 0;

        $pieChartLabels = $subscribedCounts->pluck('name')->toArray();
        $pieChartData = $subscribedCounts->pluck('count')->toArray();

        if ($nonSubscribedCount > 0) {
            $pieChartLabels[] = 'Non-Subscribed';
            $pieChartData[] = $nonSubscribedCount;
        }

        // --- THIS IS THE NEW LOGIC ---
        // Create a color map for your subscription plans
        $colorMap = [
            'Gold' => '#ffd700',   // Gold
            'Silver' => '#c0c0c0', // Silver
            'Bronze' => '#cd7f32', // Bronze
            'Non-Subscribed' => '#000000', // Black
        ];

        // Create the colors array in the correct order based on the labels
        $pieChartColors = [];
        foreach ($pieChartLabels as $label) {
            $pieChartColors[] = $colorMap[$label] ?? '#6c757d'; // Use grey as a fallback
        }

        return view('admin.dashboard', compact(
            'totalUsers', 
            'newUsersToday',
            'visitLabels',
            'visitCounts',
            'pieChartLabels',
            'pieChartData',
            'pieChartColors' // Pass the new colors array to the view
        ));
    }
}

