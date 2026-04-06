<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MatchesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = User::where('id', '!=', $user->id)
            ->where('gender', '!=', $user->gender);

        // Apply filters
        if ($request->filled('age_from')) {
            $maxDob = Carbon::now()->subYears($request->age_from)->endOfDay();
            $query->where('dob', '<=', $maxDob);
        }

        if ($request->filled('age_to')) {
            $minDob = Carbon::now()->subYears($request->age_to + 1)->startOfDay();
            $query->where('dob', '>=', $minDob);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('state')) {
            $query->where('state', 'like', '%' . $request->state . '%');
        }

        if ($request->filled('country')) {
            $query->where('country', 'like', '%' . $request->country . '%');
        }

        if ($request->filled('education')) {
            $query->where('highest_education', $request->education);
        }

        if ($request->filled('occupation')) {
            $query->where('occupation', 'like', '%' . $request->occupation . '%');
        }

        $perPage = $request->get('per_page', 20);
        $matches = $query->paginate($perPage);

        $formattedMatches = $matches->map(function($match) use ($user) {
            return $this->formatMatch($match, $user);
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'matches' => $formattedMatches,
                'current_page' => $matches->currentPage(),
                'total' => $matches->total(),
                'per_page' => $matches->perPage(),
                'last_page' => $matches->lastPage(),
            ],
        ]);
    }

    public function recommended(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 10);

        $matches = User::where('id', '!=', $user->id)
            ->where('gender', '!=', $user->gender)
            ->limit($limit)
            ->get();

        $formattedMatches = $matches->map(function($match) use ($user) {
            return $this->formatMatch($match, $user);
        });

        return response()->json([
            'status' => 'success',
            'data' => $formattedMatches,
        ]);
    }

    private function formatMatch(User $match, User $currentUser): array
    {
        $isShortlisted = DB::table('user_shortlists')
            ->where('user_id', $currentUser->id)
            ->where('shortlisted_user_id', $match->id)
            ->exists();

        return [
            'id' => $match->id,
            'full_name' => $match->full_name,
            'profile_image' => $match->profile_image ? asset('storage/' . $match->profile_image) : null,
            'age' => $match->dob ? Carbon::parse($match->dob)->age : null,
            'city' => $match->city,
            'state' => $match->state,
            'country' => $match->country,
            'location' => trim(($match->city ?? '') . ($match->city && $match->state ? ', ' : '') . ($match->state ?? '')),
            'occupation' => $match->occupation,
            'highest_education' => $match->highest_education,
            'height' => $match->height,
            'mother_tongue' => $match->mother_tongue,
            'is_shortlisted' => $isShortlisted,
        ];
    }
}

