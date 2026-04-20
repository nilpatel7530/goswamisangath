<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Redirect the user to the Google authentication page.
    public function redirectToGoogle()
    {
        \Log::info('Google Login redirection initiated.');
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            \Log::error('Google Redirect Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect('/login')->with('error', 'Unable to connect to Google: ' . $e->getMessage());
        }
    }

    // Obtain the user information from Google.
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user exists by email or google_id
            $user = User::where('email', $googleUser->email)
                        ->orWhere('google_id', $googleUser->id)
                        ->first();
            
            // If user doesn't exist, redirect to the signup page
            if (!$user) {
                session()->put('google_prefill', [
                    'email' => $googleUser->getEmail(),
                    'name' => $googleUser->getName()
                ]);
                
                return redirect()->route('signup')
                    ->with('error', 'Account not found. Please register to continue.');
            } else {
                // User exists, link google_id if it's missing
                if (empty($user->google_id)) {
                    $user->google_id = $googleUser->id;
                    $user->save();
                }
                
                // Ensure user has free plan if they don't have active membership
                $membershipService = app(\App\Services\MembershipService::class);
                $membershipService->assignFreePlan($user);
            }

            // Log the user in
            Auth::login($user);

            // Redirect to the homepage or dashboard
            return redirect('/');

        } catch (\Exception $e) {
            // If something goes wrong, redirect back to the login page with an error
            return redirect('/login')->with('error', 'Something went wrong with Google login.');
        }
    }
}