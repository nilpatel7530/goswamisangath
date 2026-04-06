<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpDeliveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class OtpController extends Controller
{
    /**
     * Send OTP to email or mobile number.
     * intent: login | signup — where to redirect after send/verify
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_or_mobile' => 'required|string',
            'intent' => 'nullable|string|in:login,signup',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $intent = $request->input('intent', 'signup');
        $emailOrMobile = trim($request->email_or_mobile);
        $isEmail = filter_var($emailOrMobile, FILTER_VALIDATE_EMAIL);
        $delivery = app(OtpDeliveryService::class);
        $method = $delivery->getMethod();

        if ($method === 'email' && ! $isEmail) {
            return back()->withErrors(['email_or_mobile' => 'OTP is sent via email. Please enter your email address.'])->withInput();
        }
        if ($method === 'sms' && $isEmail) {
            return back()->withErrors(['email_or_mobile' => 'OTP is sent via SMS. Please enter your mobile number.'])->withInput();
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Session::put('otp', $otp);
        Session::put('otp_email_or_mobile', $emailOrMobile);
        Session::put('otp_expires_at', now()->addMinutes(10));
        Session::put('otp_intent', $intent);

        $recipient = $emailOrMobile;
        if ($method === 'sms' && ! $isEmail) {
            $mobile = preg_replace('/\D/', '', $recipient);
            $recipient = strlen($mobile) > 10 ? substr($mobile, -10) : $mobile;
        }

        $result = $delivery->sendOtp($recipient, $otp);

        if ($result['success']) {
            $msg = $method === 'email'
                ? 'OTP has been sent to your email address.'
                : 'OTP has been sent to your mobile number.';
            $redirectTo = $intent === 'login' ? route('login') : route('signup');
            return redirect($redirectTo)->with('success', $msg)->with('show_otp_verification', true);
        }

        if ($method === 'sms' && config('app.env') === 'local' && ! app(\App\Services\Fast2SmsService::class)->isConfigured()) {
            $redirectTo = $intent === 'login' ? route('login') : route('signup');
            return redirect($redirectTo)
                ->with('success', 'OTP has been sent to your mobile number.')
                ->with('otp_display', $otp)
                ->with('show_otp_verification', true);
        }

        return back()->withErrors(['email_or_mobile' => $result['message'] ?? 'Failed to send OTP. Please try again.'])->withInput();
    }

    /**
     * Verify OTP and either log in (intent=login) or continue to signup (intent=signup).
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string|size:6',
            'intent' => 'nullable|string|in:login,signup',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $intent = $request->input('intent', Session::get('otp_intent', 'signup'));
        $enteredOtp = $request->otp;
        $storedOtp = Session::get('otp');
        $expiresAt = Session::get('otp_expires_at');

        if (! $storedOtp || ! $expiresAt || now()->gt($expiresAt)) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.'])->withInput();
        }

        if ($enteredOtp !== $storedOtp) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.'])->withInput();
        }

        Session::put('otp_verified', true);

        if ($intent === 'login') {
            $emailOrMobile = Session::get('otp_email_or_mobile');
            $isEmail = filter_var($emailOrMobile, FILTER_VALIDATE_EMAIL);
            $user = null;
            if ($isEmail) {
                $user = User::where('email', $emailOrMobile)->first();
            } else {
                $mobile = preg_replace('/\D/', '', $emailOrMobile);
                $mobile = strlen($mobile) > 10 ? substr($mobile, -10) : $mobile;
                $user = User::where('mobile_number', $emailOrMobile)->first()
                    ?? User::where('mobile_number', $mobile)->first()
                    ?? User::whereRaw("REPLACE(REPLACE(REPLACE(mobile_number, ' ', ''), '-', ''), '+', '') LIKE ?", ['%' . $mobile . '%'])->first();
            }
            if (! $user) {
                return back()->withErrors(['otp' => 'No account found with this ' . ($isEmail ? 'email' : 'mobile number') . '. Please sign up first.'])->withInput();
            }
            if ($user->role === 'admin') {
                Session::forget(['otp', 'otp_email_or_mobile', 'otp_expires_at', 'otp_intent', 'otp_verified']);
                return back()->withErrors(['otp' => 'Admin accounts cannot login with OTP. Use email and password.'])->withInput();
            }
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            Session::forget(['otp', 'otp_email_or_mobile', 'otp_expires_at', 'otp_intent', 'otp_verified']);
            return redirect()->route('dashboard')->with('success', 'Welcome back!');
        }

        Session::forget('otp_intent');
        return redirect()->route('signup')->with('success', 'OTP verified successfully! Please complete your registration.');
    }
}
