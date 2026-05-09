@extends('front.layouts.app')

@section('title', 'Complete Payment')

@push('styles')
<style>
    * { box-sizing: border-box; }

    .payment-page {
        padding: 60px 0 80px;
        background: #f8f8f6;
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .payment-wrap {
        max-width: 480px;
        margin: 0 auto;
        width: 100%;
    }

    .payment-card {
        background: #fff;
        border: 1px solid #e8e8e4;
        border-radius: 16px;
        overflow: hidden;
    }

    /* Header */
    .payment-header {
        padding: 24px 28px 20px;
        border-bottom: 1px solid #e8e8e4;
    }

    .payment-header-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 4px;
    }

    .payment-title {
        font-size: 18px;
        font-weight: 600;
        color: #1a1a1a;
        letter-spacing: -0.2px;
    }

    .stripe-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #635BFF;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.03em;
        padding: 4px 9px 4px 6px;
        border-radius: 5px;
        line-height: 1;
    }

    .stripe-badge svg { width: 14px; height: 14px; flex-shrink: 0; }

    .payment-sub { font-size: 13px; color: #888780; margin-top: 2px; }

    /* Order summary mini */
    .order-mini {
        padding: 16px 28px;
        background: #f8f8f6;
        border-bottom: 1px solid #e8e8e4;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .order-mini-label { font-size: 12px; color: #888780; }
    .order-mini-id    { font-size: 12px; font-weight: 500; color: #1a1a1a; }

    .order-mini-amount {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
        letter-spacing: -0.5px;
    }

    /* Body */
    .payment-body { padding: 24px 28px; }

    /* Stripe Payment Element mount point */
    #payment-element {
        margin-bottom: 20px;
    }

    /* Error message */
    .pay-error {
        background: #FFF5F5;
        border: 1px solid #F5C6C6;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 13px;
        color: #A32D2D;
        margin-bottom: 16px;
        display: none;
    }

    .pay-error.visible { display: block; }

    /* Submit button */
    .btn-pay {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px;
        background: #635BFF;
        color: #fff;
        border: none;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s, opacity 0.15s;
        letter-spacing: 0.01em;
    }

    .btn-pay:hover    { background: #4f46e5; }
    .btn-pay:disabled { opacity: 0.65; cursor: not-allowed; }

    .btn-pay svg { width: 16px; height: 16px; flex-shrink: 0; }

    /* Spinner */
    .spinner {
        display: none;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,0.4);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    .btn-pay.loading .spinner   { display: inline-block; }
    .btn-pay.loading .btn-label { display: none; }

    /* Footer */
    .payment-footer {
        padding: 14px 28px 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 11px;
        color: #888780;
    }

    .payment-footer svg { flex-shrink: 0; }

    @media (max-width: 600px) {
        .payment-page  { padding: 24px 0 40px; align-items: flex-start; }
        .payment-card  { border-radius: 0; border-left: none; border-right: none; }
        .payment-header, .payment-body { padding-left: 20px; padding-right: 20px; }
        .order-mini { padding-left: 20px; padding-right: 20px; }
        .payment-footer { padding-left: 20px; padding-right: 20px; }
    }
</style>
@endpush

@section('content')
<div class="payment-page">
    <div class="container">
        <div class="payment-wrap">
            <div class="payment-card">

                {{-- Header --}}
                <div class="payment-header">
                    <div class="payment-header-top">
                        <span class="payment-title">Complete your payment</span>
                        <span class="stripe-badge">
                            <svg viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="28" height="28" rx="6" fill="#fff" fill-opacity="0.15"/>
                                <path d="M13.37 11.16c0-.74.61-1.03 1.62-1.03 1.44 0 2.27.39 3.01.76V7.57A8.85 8.85 0 0 0 15 7c-3.19 0-5.31 1.67-5.31 4.45 0 4.34 5.97 3.65 5.97 5.52 0 .87-.76 1.15-1.82 1.15-1.57 0-3.58-.65-3.58-.65v3.32c1.2.52 2.41.74 3.58.74 3.26 0 5.5-1.62 5.5-4.43-.02-4.68-6.97-3.85-6.97-5.94z" fill="#fff"/>
                            </svg>
                            Powered by Stripe
                        </span>
                    </div>
                    <div class="payment-sub">Enter your card details to complete the order.</div>
                </div>

                {{-- Order mini summary --}}
                <div class="order-mini">
                    <div>
                        <div class="order-mini-label">Order</div>
                        <div class="order-mini-id">#{{ $order->id }}</div>
                    </div>
                    <div class="order-mini-amount">${{ number_format($order->total_amount, 2) }}</div>
                </div>

                {{-- Payment form body --}}
                <div class="payment-body">

                    <div id="pay-error" class="pay-error"></div>

                    <form id="payment-form">
                        @csrf
                        <div id="payment-element"></div>

                        <button type="submit" id="submit-btn" class="btn-pay">
                            <span class="spinner"></span>
                            <svg class="btn-label" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <rect x="2" y="5" width="20" height="14" rx="2"/>
                                <path d="M2 10h20"/>
                            </svg>
                            <span class="btn-label">Pay ${{ number_format($order->total_amount, 2) }}</span>
                        </button>
                    </form>

                </div>

                {{-- Footer --}}
                <div class="payment-footer">
                    <svg width="11" height="11" viewBox="0 0 16 16" fill="none"
                         stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="7" width="10" height="8" rx="1.5"/>
                        <path d="M5 7V5a3 3 0 016 0v2"/>
                    </svg>
                    Payments are encrypted and secure · No card data stored
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
(function () {
    const stripe      = Stripe('{{ config("services.stripe.key") }}');
    const clientSecret = '{{ $clientSecret }}';

    const appearance = {
        theme: 'stripe',
        variables: {
            colorPrimary: '#635BFF',
            colorBackground: '#ffffff',
            colorText: '#1a1a1a',
            colorDanger: '#E24B4A',
            fontFamily: 'system-ui, sans-serif',
            spacingUnit: '4px',
            borderRadius: '8px',
        },
    };

    const elements      = stripe.elements({ appearance, clientSecret });
    const paymentElement = elements.create('payment');
    paymentElement.mount('#payment-element');

    const form      = document.getElementById('payment-form');
    const submitBtn = document.getElementById('submit-btn');
    const errorDiv  = document.getElementById('pay-error');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        errorDiv.classList.remove('visible');

        const { error } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: '{{ route("front.orders.payment.callback") }}?order_id={{ $order->id }}',
            },
        });

        if (error) {
            errorDiv.textContent = error.message;
            errorDiv.classList.add('visible');
            submitBtn.disabled = false;
            submitBtn.classList.remove('loading');
        }
        // On success Stripe redirects automatically
    });
})();
</script>
@endpush