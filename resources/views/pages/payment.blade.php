@extends('layouts.app')

@section('title', 'Payment - GoswamiSangath')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-card-dark rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-card-border">
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary to-red-600 px-6 py-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">Complete Payment</h1>
                        <p class="text-red-100">Secure payment powered by Razorpay</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold">₹{{ number_format($amount, 2) }}</div>
                        <div class="text-sm text-red-100">{{ $membership->name }} Plan</div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="p-6">
                @if(session('error'))
                    <div class="mb-6 bg-red-500/20 dark:bg-red-500/20 border border-red-500 dark:border-red-500 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined">error</span>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if(!isset($orderId) || !isset($keyId))
                    <div class="mb-6 bg-yellow-500/20 dark:bg-yellow-500/20 border border-yellow-500 dark:border-yellow-500 text-yellow-700 dark:text-yellow-300 px-4 py-3 rounded-lg">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined">warning</span>
                            <span>Payment configuration error. Please contact administrator.</span>
                        </div>
                    </div>
                @endif

                <form id="payment-form">
                    @csrf
                    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="{{ $orderId ?? '' }}">
                    <input type="hidden" name="membership_id" value="{{ $membership->id }}">

                    <!-- Order Details -->
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Order Summary</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Membership Plan:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $membership->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Amount:</span>
                                <span class="font-medium text-gray-900 dark:text-white">₹{{ number_format($amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="font-semibold text-gray-900 dark:text-white">Total:</span>
                                <span class="font-bold text-primary text-lg">₹{{ number_format($amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <button type="submit" id="pay-button" class="w-full bg-primary hover:bg-red-600 text-white font-bold py-4 px-6 rounded-lg transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">lock</span>
                        <span>Pay ₹{{ number_format($amount, 2) }}</span>
                    </button>

                    <!-- Security Notice -->
                    <div class="mt-4 text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center justify-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">verified</span>
                            Your payment is secured by Razorpay. We never store your card details.
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white dark:bg-card-dark rounded-lg p-6 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4"></div>
                <p class="text-gray-900 dark:text-white font-medium">Processing payment...</p>
            </div>
        </div>
    </div>
</div>

<!-- Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('payment-form');
    const payButton = document.getElementById('pay-button');
    const loadingOverlay = document.getElementById('loading-overlay');
    const orderIdElement = document.getElementById('razorpay_order_id');
    
    if (!orderIdElement || !orderIdElement.value) {
        console.error('Payment order ID not found');
        return;
    }
    
    const orderId = orderIdElement.value;
    const amount = {{ ($amount ?? 0) * 100 }}; // Convert to paise
    const keyId = '{{ $keyId ?? '' }}';

    if (!keyId) {
        console.error('Razorpay Key ID not configured');
        alert('Payment gateway is not configured. Please contact administrator.');
        return;
    }

    // Function to open Razorpay checkout
    function openRazorpayCheckout() {
        const options = {
            key: keyId,
            amount: amount,
            currency: 'INR',
            name: 'GoswamiSangath',
            description: '{{ $membership->name }} Membership Plan',
            order_id: orderId,
            handler: function(response) {
                // Create a form to submit payment details
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("payment.success") }}';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                const orderIdInput = document.createElement('input');
                orderIdInput.type = 'hidden';
                orderIdInput.name = 'razorpay_order_id';
                orderIdInput.value = response.razorpay_order_id;
                form.appendChild(orderIdInput);

                const paymentIdInput = document.createElement('input');
                paymentIdInput.type = 'hidden';
                paymentIdInput.name = 'razorpay_payment_id';
                paymentIdInput.value = response.razorpay_payment_id;
                form.appendChild(paymentIdInput);

                const signatureInput = document.createElement('input');
                signatureInput.type = 'hidden';
                signatureInput.name = 'razorpay_signature';
                signatureInput.value = response.razorpay_signature;
                form.appendChild(signatureInput);

                document.body.appendChild(form);
                form.submit();
            },
            prefill: {
                name: '{{ $user->full_name ?? $user->name }}',
                email: '{{ $user->email }}',
                contact: '{{ $user->mobile_number ?? "" }}'
            },
            theme: {
                color: '#EC3713'
            },
            modal: {
                ondismiss: function() {
                    loadingOverlay.classList.add('hidden');
                    // Redirect back to membership page if user closes the modal
                    window.location.href = '{{ route("membership") }}';
                }
            }
        };

        const razorpay = new Razorpay(options);
        
        loadingOverlay.classList.remove('hidden');
        razorpay.open();
        
        razorpay.on('payment.failed', function(response) {
            loadingOverlay.classList.add('hidden');
            
            // Redirect to failure page
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("payment.failure") }}';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            const orderIdInput = document.createElement('input');
            orderIdInput.type = 'hidden';
            orderIdInput.name = 'razorpay_order_id';
            orderIdInput.value = orderId;
            form.appendChild(orderIdInput);

            const errorInput = document.createElement('input');
            errorInput.type = 'hidden';
            errorInput.name = 'error[description]';
            errorInput.value = response.error.description || 'Payment failed';
            form.appendChild(errorInput);

            document.body.appendChild(form);
            form.submit();
        });
        
        return razorpay;
    }

    // Auto-open Razorpay checkout when page loads
    const razorpay = openRazorpayCheckout();

    // Also handle form submission (if user clicks pay button)
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const razorpay = openRazorpayCheckout();
    });
});
</script>
@endsection
