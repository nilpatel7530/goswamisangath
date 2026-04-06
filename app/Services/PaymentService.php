<?php

namespace App\Services;

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $api;
    protected $keyId;
    protected $keySecret;

    public function __construct()
    {
        // Load credentials will be called lazily or on first use
        // This prevents issues if database isn't ready during service instantiation
    }

    /**
     * Load Razorpay credentials from database
     */
    protected function loadCredentials()
    {
        // If already loaded, don't reload
        if ($this->keyId && $this->keySecret && $this->api !== null) {
            return;
        }

        // Check if Razorpay SDK is installed
        if (!class_exists('Razorpay\Api\Api')) {
            Log::error('Razorpay SDK not installed. Please run: composer require razorpay/razorpay');
            return;
        }

        try {
            $settings = DB::table('payment_settings')
                ->where('is_active', true)
                ->first();

            if ($settings && !empty($settings->key_id) && !empty($settings->key_secret)) {
                $this->keyId = $settings->key_id;
                $this->keySecret = $settings->key_secret;
                
                try {
                    $this->api = new Api($this->keyId, $this->keySecret);
                    Log::info('Razorpay API initialized successfully');
                } catch (\Exception $e) {
                    Log::error('Failed to initialize Razorpay API: ' . $e->getMessage());
                    Log::error('Stack trace: ' . $e->getTraceAsString());
                }
            } else {
                Log::warning('Payment settings not found or incomplete in database');
            }
        } catch (\Exception $e) {
            Log::error('Error loading payment credentials: ' . $e->getMessage());
        }
    }

    /**
     * Check if Razorpay is configured
     */
    public function isConfigured(): bool
    {
        // Load credentials if not already loaded
        $this->loadCredentials();

        // Check if we have credentials
        if (empty($this->keyId) || empty($this->keySecret)) {
            return false;
        }

        // If API is not initialized, try to initialize it
        if ($this->api === null) {
            try {
                if (class_exists('Razorpay\Api\Api')) {
                    $this->api = new Api($this->keyId, $this->keySecret);
                } else {
                    Log::error('Razorpay SDK class not found');
                    return false;
                }
            } catch (\Exception $e) {
                Log::error('Failed to initialize Razorpay API: ' . $e->getMessage());
                return false;
            }
        }

        return $this->api !== null;
    }

    /**
     * Create a Razorpay order
     */
    public function createOrder($amount, $currency = 'INR', $receipt = null, $notes = [])
    {
        if (!$this->isConfigured()) {
            throw new \Exception('Razorpay is not configured. Please contact administrator.');
        }

        try {
            $orderData = [
                'amount' => $amount * 100, // Convert to paise
                'currency' => $currency,
                'receipt' => $receipt ?? 'receipt_' . time(),
                'notes' => $notes,
            ];

            $order = $this->api->order->create($orderData);
            
            return [
                'success' => true,
                'order_id' => $order['id'],
                'amount' => $order['amount'],
                'currency' => $order['currency'],
                'receipt' => $order['receipt'],
            ];
        } catch (\Exception $e) {
            Log::error('Razorpay Order Creation Failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify payment signature
     */
    public function verifyPayment($razorpayOrderId, $razorpayPaymentId, $razorpaySignature)
    {
        if (!$this->isConfigured()) {
            return false;
        }

        try {
            $attributes = [
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature,
            ];

            $this->api->utility->verifyPaymentSignature($attributes);
            return true;
        } catch (SignatureVerificationError $e) {
            Log::error('Payment Signature Verification Failed: ' . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            Log::error('Payment Verification Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get payment details from Razorpay
     */
    public function getPaymentDetails($paymentId)
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $payment = $this->api->payment->fetch($paymentId);
            return [
                'success' => true,
                'payment' => $payment->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('Fetch Payment Details Failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create a transaction record
     */
    public function createTransaction($userId, $membershipId, $razorpayOrderId, $amount, $currency = 'INR')
    {
        return DB::table('payment_transactions')->insertGetId([
            'user_id' => $userId,
            'membership_id' => $membershipId,
            'razorpay_order_id' => $razorpayOrderId,
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Update transaction status
     */
    public function updateTransaction($razorpayOrderId, $status, $razorpayPaymentId = null, $razorpaySignature = null, $razorpayResponse = null, $failureReason = null)
    {
        $updateData = [
            'status' => $status,
            'updated_at' => now(),
        ];

        if ($razorpayPaymentId) {
            $updateData['razorpay_payment_id'] = $razorpayPaymentId;
        }

        if ($razorpaySignature) {
            $updateData['razorpay_signature'] = $razorpaySignature;
        }

        if ($razorpayResponse) {
            $updateData['razorpay_response'] = json_encode($razorpayResponse);
        }

        if ($failureReason) {
            $updateData['failure_reason'] = $failureReason;
        }

        if ($status === 'success') {
            $updateData['paid_at'] = now();
        }

        DB::table('payment_transactions')
            ->where('razorpay_order_id', $razorpayOrderId)
            ->update($updateData);
    }

    /**
     * Get key ID for frontend
     */
    public function getKeyId()
    {
        return $this->keyId;
    }
}
