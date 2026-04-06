<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OtpDeliveryService
{
    protected ?object $settings = null;

    public function getMethod(): string
    {
        $s = $this->getSettings();
        return $s && in_array($s->otp_method, ['sms', 'email'], true) ? $s->otp_method : 'sms';
    }

    public function getSettings(): ?object
    {
        if ($this->settings === null) {
            $this->settings = DB::table('otp_settings')->first();
        }
        return $this->settings;
    }

    /**
     * Send OTP to the given recipient (email or mobile based on current method).
     */
    public function sendOtp(string $recipient, string $otp): array
    {
        $method = $this->getMethod();

        if ($method === 'email') {
            return $this->sendOtpByEmail($recipient, $otp);
        }

        return $this->sendOtpBySms($recipient, $otp);
    }

    protected function sendOtpBySms(string $mobile, string $otp): array
    {
        $sms = app(Fast2SmsService::class);
        if (! $sms->isConfigured()) {
            return ['success' => false, 'message' => 'SMS gateway not configured.'];
        }
        return $sms->sendOtp($mobile, $otp);
    }

    /**
     * Send OTP by email using Laravel default mailer (config from .env MAIL_*).
     */
    protected function sendOtpByEmail(string $email, string $otp): array
    {
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');
        if (empty($fromAddress)) {
            return ['success' => false, 'message' => 'Email OTP: set MAIL_FROM_ADDRESS in .env.'];
        }

        try {
            Mail::raw(
                "Your OTP code is: {$otp}. This code will expire in 10 minutes. Do not share.",
                function ($message) use ($email, $fromAddress, $fromName) {
                    $message->to($email)
                        ->from($fromAddress, $fromName)
                        ->subject('Your OTP Code - ' . config('app.name'));
                }
            );
            return ['success' => true, 'message' => 'OTP sent successfully.'];
        } catch (\Throwable $e) {
            Log::error('OtpDeliveryService email send failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return ['success' => false, 'message' => $this->friendlyMailError($e->getMessage())];
        }
    }

    private function friendlyMailError(string $msg): string
    {
        if (str_contains($msg, '535') || str_contains($msg, 'BadCredentials') || str_contains($msg, 'Username and Password not accepted')) {
            return 'Unable to send OTP email. Please try again later.';
        }
        return 'Failed to send OTP email. Please try again.';
    }
}
