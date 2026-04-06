<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $user = Auth::user();

        $query = User::where('id', '!=', $user->id);

        // Looking For filter (Bride/Groom) - takes priority
        if ($request->filled('looking_for')) {
            $lookingFor = strtolower($request->looking_for);
            if ($lookingFor === 'bride') {
                $query->where('gender', 'female');
            } elseif ($lookingFor === 'groom') {
                $query->where('gender', 'male');
            }
        } elseif ($request->filled('gender')) {
            // Legacy support for 'gender' parameter
            $gender = strtolower($request->gender);
            if ($gender === 'bride' || $gender === 'female') {
                $query->where('gender', 'female');
            } elseif ($gender === 'groom' || $gender === 'male') {
                $query->where('gender', 'male');
            }
        } else {
            // Default: show opposite gender
            $query->where('gender', '!=', $user->gender);
        }

        // Age filter
        if ($request->filled('age_from')) {
            $maxDob = Carbon::now()->subYears($request->age_from)->endOfDay();
            $query->where('dob', '<=', $maxDob);
        }

        if ($request->filled('age_to')) {
            $minDob = Carbon::now()->subYears($request->age_to + 1)->startOfDay();
            $query->where('dob', '>=', $minDob);
        }

        // Location filters
        if ($request->filled('state')) {
            $query->where('state', 'like', '%' . $request->state . '%');
        }

        if ($request->filled('country')) {
            $query->where('country', 'like', '%' . $request->country . '%');
        }

        // Education filter (supports array for multiple selections)
        if ($request->filled('education')) {
            $educations = is_array($request->education) ? $request->education : [$request->education];
            $query->whereIn('highest_education', $educations);
        }

        // Occupation filter
        if ($request->filled('occupation')) {
            $query->where('occupation', 'like', '%' . $request->occupation . '%');
        }

        // Height filter
        if ($request->filled('height_min')) {
            $query->where('height', '>=', $request->height_min);
        }

        if ($request->filled('height_max')) {
            $query->where('height', '<=', $request->height_max);
        }

        // Marital status (supports array for multiple selections)
        if ($request->filled('marital_status')) {
            $statuses = is_array($request->marital_status) ? $request->marital_status : [$request->marital_status];
            $query->whereIn('marital_status', $statuses);
        }

        // Mother tongue
        if ($request->filled('mother_tongue')) {
            $query->where('mother_tongue', $request->mother_tongue);
        }

        // City filter (supports array for multiple cities)
        if ($request->filled('city')) {
            $cities = is_array($request->city) ? $request->city : [$request->city];
            $query->where(function($q) use ($cities) {
                foreach ($cities as $city) {
                    $q->orWhere('city', 'like', '%' . $city . '%');
                }
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'relevance');
        switch ($sortBy) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'age_asc':
                $query->orderBy('dob', 'desc');
                break;
            case 'age_desc':
                $query->orderBy('dob', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $perPage = $request->get('per_page', 20);
        $results = $query->paginate($perPage);

        $formatted = $results->map(function($result) {
            return [
                'id' => $result->id,
                'full_name' => $result->full_name,
                'profile_image' => $result->profile_image ? asset('storage/' . $result->profile_image) : null,
                'age' => $result->dob ? Carbon::parse($result->dob)->age : null,
                'city' => $result->city,
                'state' => $result->state,
                'country' => $result->country,
                'occupation' => $result->occupation,
                'highest_education' => $result->highest_education,
                'height' => $result->height,
                'mother_tongue' => $result->mother_tongue,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'results' => $formatted,
                'current_page' => $results->currentPage(),
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'last_page' => $results->lastPage(),
            ],
        ]);
    }
}

