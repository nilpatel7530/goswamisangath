<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

            if (!$this->paymentService->isConfigured()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment gateway is not configured. Please contact administrator.',
                ], 400);
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
                Log::error('Failed to create Razorpay order: ' . ($orderResult['message'] ?? 'Unknown error'));
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to create payment order: ' . ($orderResult['message'] ?? 'Unknown error'),
                ], 500);
            }

            // Create transaction record
            $this->paymentService->createTransaction(
                $user->id,
                $membership->id,
                $orderResult['order_id'],
                $membership->price
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Payment order created successfully',
                'data' => [
                    'order_id' => $orderResult['order_id'],
                    'amount' => (float) $membership->price,
                    'amount_in_paise' => (int) ($membership->price * 100), // For Razorpay SDK
                    'currency' => 'INR',
                    'key_id' => $this->paymentService->getKeyId(),
                    'membership' => [
                        'id' => $membership->id,
                        'name' => $membership->name,
                        'price' => (float) $membership->price,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Order Creation Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing your payment request.',
            ], 500);
        }
    }

    /**
     * Verify and process payment
     */
    public function verifyPayment(Request $request)
    {
        try {
            $request->validate([
                'razorpay_order_id' => 'required|string',
                'razorpay_payment_id' => 'required|string',
                'razorpay_signature' => 'required|string',
            ]);

            $razorpayOrderId = $request->input('razorpay_order_id');
            $razorpayPaymentId = $request->input('razorpay_payment_id');
            $razorpaySignature = $request->input('razorpay_signature');

            // Get transaction details first to verify ownership
            $transaction = DB::table('payment_transactions')
                ->where('razorpay_order_id', $razorpayOrderId)
                ->first();

            if (!$transaction) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction not found.',
                ], 404);
            }

            // Verify that the transaction belongs to the authenticated user
            $user = Auth::user();
            if ($transaction->user_id != $user->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access to this transaction.',
                ], 403);
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
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment verification failed. Invalid signature.',
                ], 400);
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
            $membership = Membership::find($transaction->membership_id);

            if (!$membership) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Membership plan not found.',
                ], 404);
            }

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
                'expires_at' => $membership->price > 0 ? now()->addDays(30) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment successful! Membership activated.',
                'data' => [
                    'membership' => [
                        'id' => $membership->id,
                        'name' => $membership->name,
                        'price' => (float) $membership->price,
                        'visits_allowed' => $membership->visits_allowed,
                        'expires_at' => $membership->price > 0 ? now()->addDays(30)->toDateTimeString() : null,
                    ],
                    'transaction' => [
                        'order_id' => $razorpayOrderId,
                        'payment_id' => $razorpayPaymentId,
                        'status' => 'success',
                    ],
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Payment Verification Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while verifying payment.',
            ], 500);
        }
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

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully subscribed to ' . $membership->name . ' plan',
            'data' => [
                'membership' => [
                    'id' => $membership->id,
                    'name' => $membership->name,
                    'expires_at' => null,
                ],
            ],
        ]);
    }
}
