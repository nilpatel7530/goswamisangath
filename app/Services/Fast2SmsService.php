<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Fast2SmsService
{
    protected string $apiKey;

    protected string $baseUrl = 'https://www.fast2sms.com/dev';

    public function __construct(?string $apiKey = null)
    {
        $this->apiKey = $apiKey ?? config('services.fast2sms.api_key', '');
    }

    /**
     * Check if Fast2SMS is configured (API key set).
     */
    public function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }

    /**
     * Send OTP SMS to one or more mobile numbers.
     *
     * @param  string|array  $numbers  Mobile number(s), e.g. "9876543210" or ["9876543210", "9123456789"]
     * @param  string  $otp  Numeric OTP (up to 10 digits)
     * @return array{success: bool, message: string, response?: array}
     */
    public function sendOtp($numbers, string $otp): array
    {
        if (! $this->isConfigured()) {
            Log::warning('Fast2SMS: API key not set. Skipping OTP send.');
            return [
                'success' => false,
                'message' => 'SMS gateway not configured.',
            ];
        }

        $numbersList = is_array($numbers) ? implode(',', $numbers) : $numbers;
        $numbersList = preg_replace('/\D/', '', $numbersList); // digits only

        if (empty($numbersList)) {
            return [
                'success' => false,
                'message' => 'Invalid mobile number(s).',
            ];
        }

        try {
            // Fast2SMS OTP route: delivers as "Your OTP: {variables_values}"
            $response = Http::withHeaders([
                'authorization' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/bulkV2", [
                'variables_values' => $otp,
                'route' => 'otp',
                'numbers' => $numbersList,
            ]);

            $body = $response->json();

            if ($response->successful()) {
                $ok = isset($body['return']) && $body['return'] === true;
                return [
                    'success' => $ok,
                    'message' => $ok ? 'OTP sent successfully.' : ($body['message'] ?? 'Failed to send OTP.'),
                    'response' => $body,
                ];
            }

            Log::warning('Fast2SMS API error', [
                'status' => $response->status(),
                'body' => $body,
            ]);

            return [
                'success' => false,
                'message' => $body['message'] ?? 'Failed to send OTP.',
                'response' => $body,
            ];
        } catch (\Throwable $e) {
            Log::error('Fast2SMS exception: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'SMS service error. Please try again.',
            ];
        }
    }
}
