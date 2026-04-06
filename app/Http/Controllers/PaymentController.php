<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Create a payment order
     */
    public function createOrder(Request $request, Membership $membership)
    {
        try {
            $user = Auth::user();

            if ($membership->price <= 0) {
                // Free plan - no payment needed
                return $this->handleFreePlan($membership);
            }

            // Debug: Check if service is configured
            Log::info('Checking payment service configuration...');
            Log::info('Is Configured: ' . ($this->paymentService->isConfigured() ? 'YES' : 'NO'));
            Log::info('Key ID: ' . ($this->paymentService->getKeyId() ?? 'NULL'));

            if (!$this->paymentService->isConfigured()) {
                // Check database directly
                $settings = DB::table('payment_settings')->where('is_active', true)->first();
                Log::info('Database settings: ' . json_encode($settings));
                
                return redirect()->route('membership')->with('error', 'Payment gateway is not configured. Please contact administrator.');
            }

            // Create Razorpay order
            $orderResult = $this->paymentService->createOrder(
                $membership->price,
                'INR',
                'receipt_' . $user->id . '_' . $membership->id . '_' . time(),
                [
                    'user_id' => $user->id,
                    'membership_id' => $membership->id,
                    'membership_name' => $membership->name,
                ]
            );

            if (!$orderResult['success']) {
                return redirect()->route('membership')->with('error', 'Failed to create payment order: ' . ($orderResult['message'] ?? 'Unknown error'));
            }

            // Create transaction record
            $this->paymentService->createTransaction(
                $user->id,
                $membership->id,
                $orderResult['order_id'],
                $membership->price
            );

            return view('pages.payment', [
                'membership' => $membership,
                'orderId' => $orderResult['order_id'],
                'amount' => $membership->price,
                'keyId' => $this->paymentService->getKeyId(),
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Order Creation Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('membership')->with('error', 'An error occurred while processing your payment. Please try again later.');
        }
    }

    /**
     * Handle payment success callback
     */
    public function success(Request $request)
    {
        $razorpayOrderId = $request->input('razorpay_order_id');
        $razorpayPaymentId = $request->input('razorpay_payment_id');
        $razorpaySignature = $request->input('razorpay_signature');

        if (!$razorpayOrderId || !$razorpayPaymentId || !$razorpaySignature) {
            return redirect()->route('membership')->with('error', 'Invalid payment response.');
        }

        // Verify payment signature
        $isValid = $this->paymentService->verifyPayment(
            $razorpayOrderId,
            $razorpayPaymentId,
            $razorpaySignature
        );

        if (!$isValid) {
            $this->paymentService->updateTransaction(
                $razorpayOrderId,
                'failed',
                $razorpayPaymentId,
                $razorpaySignature,
                $request->all(),
                'Signature verification failed'
            );
            return redirect()->route('membership')->with('error', 'Payment verification failed.');
        }

        // Get transaction details
        $transaction = DB::table('payment_transactions')
            ->where('razorpay_order_id', $razorpayOrderId)
            ->first();

        if (!$transaction) {
            return redirect()->route('membership')->with('error', 'Transaction not found.');
        }

        // Update transaction
        $this->paymentService->updateTransaction(
            $razorpayOrderId,
            'success',
            $razorpayPaymentId,
            $razorpaySignature,
            $request->all()
        );

        // Activate membership
        $user = Auth::user();
        $membership = Membership::find($transaction->membership_id);

        // Deactivate existing memberships
        DB::table('user_memberships')
            ->where('user_id', $user->id)
            ->where('is_active', 1)
            ->update(['is_active' => 0]);

        // Create new active membership
        DB::table('user_memberships')->insert([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'is_active' => 1,
            'visits_used' => 0,
            'purchased_at' => now(),
            'expires_at' => now()->addDays(30),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('status', "Payment successful! You have successfully subscribed to the {$membership->name} plan!");
    }

    /**
     * Handle payment failure callback
     */
    public function failure(Request $request)
    {
        $razorpayOrderId = $request->input('razorpay_order_id');
        $errorDescription = $request->input('error.description', 'Payment failed');

        if ($razorpayOrderId) {
            $this->paymentService->updateTransaction(
                $razorpayOrderId,
                'failed',
                null,
                null,
                $request->all(),
                $errorDescription
            );
        }

        return redirect()->route('membership')->with('error', 'Payment failed: ' . $errorDescription);
    }

    /**
     * Handle free plan subscription
     */
    protected function handleFreePlan(Membership $membership)
    {
        $user = Auth::user();

        // Deactivate existing memberships
        DB::table('user_memberships')
            ->where('user_id', $user->id)
            ->where('is_active', 1)
            ->update(['is_active' => 0]);

        // Create new active membership
        DB::table('user_memberships')->insert([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'is_active' => 1,
            'visits_used' => 0,
            'purchased_at' => now(),
            'expires_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('status', "You have successfully subscribed to the {$membership->name} plan!");
    }
}
