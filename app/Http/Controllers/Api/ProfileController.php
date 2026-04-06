<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'data' => $this->formatUserProfile($user),
        ]);
    }

    public function view(User $user)
    {
        $currentUser = Auth::user();
        
        if ($user->id === $currentUser->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Use /api/profile to view your own profile',
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $this->formatUserProfile($user),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile_number' => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
            'gender' => 'nullable|in:male,female',
            'height' => 'nullable|string|max:255',
            'weight' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'birth_time' => 'nullable|string|max:255',
            'birth_place' => 'nullable|string|max:255',
            'raashi' => 'nullable|string|max:255',
            'naadi' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:255',
            'mother_tongue' => 'nullable|string|max:255',
            'physically_handicap' => 'nullable|in:yes,no',
            'diet' => 'nullable|string|max:255',
            'languages_known' => 'nullable|string',
            'highest_education' => 'nullable|string|max:255',
            'education_details' => 'nullable|string|max:255',
            'employed_in' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'annual_income' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $this->formatUserProfile($user),
        ]);
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
        ]);

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Store new photo
        $path = $request->file('photo')->store('profiles', 'public');
        
        // Add watermark to the uploaded image
        $imagePath = storage_path('app/public/' . $path);
        \App\Services\WatermarkService::addWatermark($imagePath);
        
        $user->update(['profile_image' => $path]);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile photo uploaded successfully',
            'data' => [
                'profile_image' => asset('storage/' . $path),
            ],
        ]);
    }

    private function formatUserProfile(User $user): array
    {
        return [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'mobile_number' => $user->mobile_number,
            'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
            'gender' => $user->gender,
            'height' => $user->height,
            'weight' => $user->weight,
            'dob' => $user->dob,
            'age' => $user->dob ? Carbon::parse($user->dob)->age : null,
            'birth_time' => $user->birth_time,
            'birth_place' => $user->birth_place,
            'raashi' => $user->raashi,
            'naadi' => $user->naadi,
            'marital_status' => $user->marital_status,
            'mother_tongue' => $user->mother_tongue,
            'physically_handicap' => $user->physically_handicap,
            'diet' => $user->diet,
            'languages_known' => $user->languages_known,
            'highest_education' => $user->highest_education,
            'education_details' => $user->education_details,
            'employed_in' => $user->employed_in,
            'occupation' => $user->occupation,
            'annual_income' => $user->annual_income,
            'country' => $user->country,
            'state' => $user->state,
            'city' => $user->city,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }
}

