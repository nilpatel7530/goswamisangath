<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'mobile_number' => ['required', 'string', 'max:20', 'unique:users'],
            'profile_image' => ['nullable', 'string'],
        ]);

        $imagePath = null;
        if ($request->filled('profile_image')) {
            $imageData = $request->profile_image;
            $image = str_replace('data:image/jpeg;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '.jpeg';
            $fullPath = storage_path('app/public/profiles/' . $imageName);
            File::put($fullPath, base64_decode($image));
            
            // Add watermark
            \App\Services\WatermarkService::addWatermark($fullPath);
            
            $imagePath = 'profiles/' . $imageName;
        }

        // Create the new user (free plan will be assigned automatically via model event)
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_image' => $imagePath, // Ensure this line is present
            // Add all other fields from your form
            'gender' => $request->gender,
            'height' => $request->height,
            'weight' => $request->weight,
            'dob' => $request->birth_year . '-' . $request->birth_month . '-' . $request->birth_day,
            'marital_status' => $request->marital_status,
            'highest_education' => $request->highest_education,
            'education_details' => $request->education_details,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'mobile_number' => $request->mobile_number,
        ]);

        Auth::login($user);

        return redirect()->route('matches');
    }
}
