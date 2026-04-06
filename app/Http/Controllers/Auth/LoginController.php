<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Handle a user's authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        // 1. Validate the incoming request data
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable'],
        ]);
        
        // Convert remember checkbox value to boolean
        // HTML checkboxes send "on" when checked, or nothing when unchecked
        $remember = $request->has('remember') && $request->remember === 'on';

        // 2. Attempt to authenticate the user
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $remember)) {
            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            // 3. Get the authenticated user and check their role
            $user = Auth::user();
            
            // Ensure we have fresh data from database
            $user->refresh();
            
            // 4. Redirect based on user role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome back, Admin!');
            }

                // Regular users go to their dashboard
                return redirect()->route('dashboard')
                    ->with('success', 'Welcome back!');
        }

        // 5. Authentication failed - return with error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Log the user out.
        Auth::logout();

        // Invalidate the session to clear all session data.
        $request->session()->invalidate();

        // Regenerate the CSRF token for security.
        $request->session()->regenerateToken();

        // Redirect the user to the homepage.
        return redirect('/');
    }
}

