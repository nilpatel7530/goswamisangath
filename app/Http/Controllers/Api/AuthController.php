<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Fast2SmsService;
use App\Services\OtpDeliveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'mobile_number' => 'nullable|string|unique:users',
            'gender' => 'required|in:male,female',
            'dob' => 'required|date',
            'country' => 'required|string',
            'state' => 'required|string',
            'city' => 'required|string',
        ]);

        $user = User::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'mobile_number' => $validated['mobile_number'] ?? null,
            'gender' => $validated['gender'],
            'dob' => $validated['dob'],
            'country' => $validated['country'],
            'state' => $validated['state'],
            'city' => $validated['city'],
            'role' => 'user',
        ]);

        $token = $this->generateApiToken($user);

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user' => $this->formatUser($user),
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password',
            ], 401);
        }

        $user = Auth::user();
        $user->refresh();

        if ($user->role === 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Admin users cannot login via API',
            ], 403);
        }

        $token = $this->generateApiToken($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => $this->formatUser($user),
                'token' => $token,
            ],
        ]);
    }

    /**
     * Logout user
     */
    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        // Revoke current token
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ]);
    }

    // ... (keeping other methods as they are mostly fine, just check generateApiToken usage)



    /**
     * Get authenticated user
     */
    public function user(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $this->formatUser($request->user()),
            ],
        ]);
    }

    /**
     * Send OTP (SMS or Email based on admin OTP settings)
     */
    public function sendOtp(Request $request)
    {
        $delivery = app(OtpDeliveryService::class);
        $method = $delivery->getMethod();

        if ($method === 'email') {
            $validated = $request->validate([
                'email' => 'required|email',
            ]);
            $recipient = $validated['email'];
        } else {
            $validated = $request->validate([
                'mobile_number' => 'required|string',
            ]);
            $mobile = preg_replace('/\D/', '', $validated['mobile_number']);
            if (strlen($mobile) < 10) {
                return response()->json(['status' => 'error', 'message' => 'Invalid mobile number'], 422);
            }
            if (strlen($mobile) > 10) {
                $mobile = substr($mobile, -10);
            }
            $recipient = $mobile;
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        cache()->put("otp_{$recipient}", $otp, now()->addMinutes(10));

        $result = $delivery->sendOtp($recipient, $otp);

        if ($result['success']) {
            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent successfully',
                'data' => ['expires_in' => 600],
            ]);
        }

        if ($method === 'sms' && ! app(Fast2SmsService::class)->isConfigured() && config('app.env') === 'local') {
            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent (dev mode)',
                'data' => ['otp' => $otp, 'expires_in' => 600],
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => $result['message'] ?? 'Failed to send OTP',
        ], 502);
    }

    /**
     * Verify OTP (supports both mobile and email based on admin OTP method)
     */
    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'otp' => 'required|string|size:6',
            'mobile_number' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        if (empty($validated['mobile_number']) && empty($validated['email'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Provide mobile_number or email',
            ], 422);
        }

        $recipient = $validated['email'] ?? null;
        if (! $recipient) {
            $mobile = preg_replace('/\D/', '', $validated['mobile_number']);
            $recipient = strlen($mobile) > 10 ? substr($mobile, -10) : $mobile;
        }

        $storedOtp = cache()->get("otp_{$recipient}");
        if (! $storedOtp || $storedOtp !== $validated['otp']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired OTP',
            ], 400);
        }

        cache()->forget("otp_{$recipient}");

        $user = null;
        if (! empty($validated['email'])) {
            $user = User::where('email', $validated['email'])->first();
            if (! $user) {
                $user = User::create([
                    'full_name' => 'User ' . substr($validated['email'], 0, strpos($validated['email'], '@')),
                    'email' => $validated['email'],
                    'mobile_number' => null,
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'user',
                ]);
            }
        } else {
            $user = User::where('mobile_number', $validated['mobile_number'])->first()
                ?? User::where('mobile_number', $recipient)->first();
            if (! $user) {
                $user = User::create([
                    'full_name' => 'User ' . substr($recipient, -4),
                    'mobile_number' => $validated['mobile_number'],
                    'email' => $recipient . '@mobile.temp',
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'user',
                ]);
            }
        }

        $token = $this->generateApiToken($user);

        return response()->json([
            'status' => 'success',
            'message' => 'OTP verified successfully',
            'data' => [
                'user' => $this->formatUser($user),
                'token' => $token,
            ],
        ]);
    }

    /**
     * Google OAuth login
     */
    public function googleLogin(Request $request)
    {
        $validated = $request->validate([
            'access_token' => 'required|string',
        ]);

        try {
            // Get user info from Google
            $googleUser = Socialite::driver('google')->userFromToken($validated['access_token']);

            // Find or create user
            $user = User::firstOrNew(['google_id' => $googleUser->id]);
            
            if (!$user->exists) {
                $user->fill([
                    'full_name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(16)),
                ]);
                $user->save();
            } else {
                $user->update([
                    'full_name' => $googleUser->name,
                    'email' => $googleUser->email,
                ]);
            }

            $token = $this->generateApiToken($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Google login successful',
                'data' => [
                    'user' => $this->formatUser($user),
                    'token' => $token,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Google authentication failed',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Refresh API token
     */
    public function refreshToken(Request $request)
    {
        $user = $request->user();
        $token = $this->generateApiToken($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Token refreshed successfully',
            'data' => [
                'token' => $token,
            ],
        ]);
    }

    /**
     * Generate API token for user
     */
    private function generateApiToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }

    /**
     * Format user data for API response
     */
    private function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'mobile_number' => $user->mobile_number,
            'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
            'gender' => $user->gender,
            'dob' => $user->dob,
            'age' => $user->dob ? \Carbon\Carbon::parse($user->dob)->age : null,
            'country' => $user->country,
            'state' => $user->state,
            'city' => $user->city,
            'role' => $user->role,
            'created_at' => $user->created_at,
        ];
    }
}

