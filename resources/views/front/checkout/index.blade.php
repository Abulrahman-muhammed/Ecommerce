@extends('front.layouts.app')

@section('title', 'Checkout')

@push('styles')
<style>
    * { box-sizing: border-box; }

    .checkout-page {
        padding: 40px 0 60px;
        background: #f8f8f6;
        min-height: 100vh;
    }

    .checkout-title {
        font-size: 26px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 28px;
        letter-spacing: -0.3px;
    }

    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 24px;
        align-items: start;
    }

    .section-card {
        background: #ffffff;
        border: 1px solid #e8e8e4;
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 16px;
    }

    .section-title {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #888780;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .step-num {
        width: 22px;
        height: 22px;
        background: #E6F1FB;
        color: #185FA5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 600;
        flex-shrink: 0;
    }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .form-grid.full { grid-template-columns: 1fr; }

    .field { display: flex; flex-direction: column; gap: 5px; }

    .field label { font-size: 11px; font-weight: 500; color: #5F5E5A; letter-spacing: 0.04em; }

    .field .form-control {
        height: 42px;
        padding: 0 13px;
        border: 1px solid #D3D1C7;
        border-radius: 8px;
        background: #f8f8f6;
        color: #1a1a1a;
        font-size: 14px;
        transition: border-color 0.15s, background 0.15s, box-shadow 0.15s;
    }

    .field .form-control:focus {
        outline: none;
        border-color: #378ADD;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(55,138,221,0.1);
    }

    .field .form-control.is-invalid { border-color: #E24B4A; background: #FFF5F5; }

    .invalid-feedback { font-size: 11px; color: #A32D2D; margin-top: 3px; }

    /* Payment options */
    .payment-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 16px;
    }

    .pay-opt {
        border: 0.5px solid #D3D1C7;
        border-radius: 12px;
        padding: 14px 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: border-color 0.15s, background 0.15s;
        background: #fff;
    }

    .pay-opt input[type="radio"] { display: none; }

    .pay-opt.selected { border: 1.5px solid #378ADD; background: #E6F1FB; }

    .pay-icon {
        width: 40px; height: 40px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .pay-icon.stripe { background: #635BFF; }
    .pay-icon.cash   { background: #EAF3DE; }
    .pay-icon svg    { width: 18px; height: 18px; }

    .pay-text { flex: 1; min-width: 0; }
    .pay-label { font-size: 13px; font-weight: 600; color: #1a1a1a; }
    .pay-sub   { font-size: 11px; color: #888780; margin-top: 2px; }

    .pay-check {
        width: 16px; height: 16px;
        border-radius: 50%;
        border: 1.5px solid #D3D1C7;
        margin-left: auto; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        transition: background 0.15s, border-color 0.15s;
    }

    .pay-opt.selected .pay-check { background: #378ADD; border-color: #378ADD; }
    .pay-check-inner { width: 6px; height: 6px; border-radius: 50%; background: #fff; }

    /* Panels */
    .pay-panel { display: none; }
    .pay-panel.active { display: block; }

    .pay-panel-inner {
        background: #f8f8f6;
        border: 1px solid #e8e8e4;
        border-radius: 10px;
        padding: 16px;
    }

    /* Stripe panel */
    .stripe-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .stripe-panel-title { font-size: 13px; font-weight: 600; color: #1a1a1a; }

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
        user-select: none;
    }

    .stripe-badge svg { width: 14px; height: 14px; flex-shrink: 0; }

    .stripe-panel-desc { font-size: 12px; color: #5F5E5A; line-height: 1.55; margin-bottom: 14px; }

    .card-brands { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }

    .brand-pill {
        height: 22px;
        padding: 0 8px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.5px;
        line-height: 1;
    }

    .brand-visa { background: #1A1F71; color: #fff; font-style: italic; font-size: 12px; letter-spacing: 1px; }
    .brand-mc   { background: #FF5F00; color: #fff; gap: 4px; }
    .brand-amex { background: #2E77BC; color: #fff; }

    .mc-circles { display: inline-flex; align-items: center; }
    .mc-circle  { width: 13px; height: 13px; border-radius: 50%; display: inline-block; }
    .mc-circle-red    { background: #EB001B; }
    .mc-circle-orange { background: #F79E1B; margin-left: -5px; }

    .secure-line {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        color: #3B6D11;
        margin-top: 12px;
    }

    /* Cash panel */
    .cash-note { display: flex; align-items: flex-start; gap: 12px; }

    .cash-note-icon {
        width: 40px; height: 40px;
        border-radius: 8px;
        background: #EAF3DE;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .cash-note-icon svg { width: 20px; height: 20px; }
    .cash-note-title { font-size: 13px; font-weight: 600; color: #1a1a1a; margin-bottom: 3px; }
    .cash-note-sub   { font-size: 12px; color: #5F5E5A; line-height: 1.5; }

    /* Cart sidebar */
    .cart-card {
        background: #fff;
        border: 1px solid #e8e8e4;
        border-radius: 14px;
        overflow: hidden;
        position: sticky;
        top: 24px;
    }

    .cart-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e8e8e4;
        font-size: 11px;
        font-weight: 600;
        color: #888780;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .cart-count-badge {
        background: #E6F1FB;
        color: #185FA5;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 9px;
        border-radius: 20px;
        letter-spacing: 0;
        text-transform: none;
    }

    .cart-items-list { padding: 8px 20px; }

    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 11px 0;
        border-bottom: 1px solid #f1efe8;
    }

    .cart-item:last-child { border-bottom: none; }

    .item-name  { font-size: 13px; font-weight: 500; color: #1a1a1a; }
    .item-qty   { font-size: 11px; color: #888780; margin-top: 2px; }
    .item-price { font-size: 13px; font-weight: 500; color: #1a1a1a; white-space: nowrap; }

    .cart-footer {
        padding: 16px 20px;
        border-top: 1px solid #e8e8e4;
        background: #f8f8f6;
    }

    .cart-summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        color: #888780;
        margin-bottom: 6px;
    }

    .cart-summary-row .val { color: #1a1a1a; }
    .free-shipping { color: #3B6D11; font-weight: 500; }

    .cart-total-row {
        display: flex;
        justify-content: space-between;
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
        padding-top: 12px;
        border-top: 1px solid #D3D1C7;
        margin-top: 10px;
    }

    .btn-place-order {
        display: block;
        width: 100%;
        margin-top: 16px;
        padding: 14px;
        background: #185FA5;
        color: #fff;
        border: none;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s;
        text-align: center;
        letter-spacing: 0.01em;
    }

    .btn-place-order:hover    { background: #0C447C; }
    .btn-place-order:disabled { opacity: 0.65; cursor: not-allowed; }

    .ssl-note {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        margin-top: 12px;
        font-size: 11px;
        color: #888780;
    }

    @media (max-width: 900px) {
        .checkout-grid   { grid-template-columns: 1fr; }
        .form-grid       { grid-template-columns: 1fr; }
        .payment-options { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="checkout-page">
    <div class="container">

        <h2 class="checkout-title">Checkout</h2>

        @if(session('error'))
            <div class="alert alert-danger mb-4">{{ session('error') }}</div>
        @endif

        <form action="{{ route('front.checkout.store') }}" method="POST" id="checkout-form">
            @csrf

            <div class="checkout-grid">

                {{-- LEFT COLUMN --}}
                <div>

                    {{-- 1. Personal Info --}}
                    <div class="section-card">
                        <div class="section-title">
                            <span class="step-num">1</span>
                            Personal information
                        </div>
                        <div class="form-grid">
                            <div class="field">
                                <label for="first_name">First name</label>
                                <input type="text" id="first_name" name="first_name"
                                    value="{{ old('first_name', auth()->user()?->first_name) }}"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    placeholder="John">
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="last_name">Last name</label>
                                <input type="text" id="last_name" name="last_name"
                                    value="{{ old('last_name', auth()->user()?->last_name) }}"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    placeholder="Smith">
                                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="email">Email address</label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', auth()->user()?->email) }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="john@example.com">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="phone">Phone number</label>
                                <input type="tel" id="phone" name="phone"
                                    value="{{ old('phone', auth()->user()?->phone) }}"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="+1 (555) 000-0000">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- 2. Shipping Address --}}
                    <div class="section-card">
                        <div class="section-title">
                            <span class="step-num">2</span>
                            Shipping address
                        </div>
                        <div class="form-grid full" style="margin-bottom:12px;">
                            <div class="field">
                                <label for="address">Street address</label>
                                <input type="text" id="address" name="address"
                                    value="{{ old('address') }}"
                                    class="form-control @error('address') is-invalid @enderror"
                                    placeholder="123 Main Street, Apt 4B">
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="field">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city"
                                    value="{{ old('city') }}"
                                    class="form-control @error('city') is-invalid @enderror"
                                    placeholder="New York">
                                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="state">State / Province</label>
                                <input type="text" id="state" name="state"
                                    value="{{ old('state') }}"
                                    class="form-control @error('state') is-invalid @enderror"
                                    placeholder="NY">
                                @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="postal_code">Postal code</label>
                                <input type="text" id="postal_code" name="postal_code"
                                    value="{{ old('postal_code') }}"
                                    class="form-control @error('postal_code') is-invalid @enderror"
                                    placeholder="10001">
                                @error('postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="country">Country</label>
                                <input type="text" id="country" name="country"
                                    value="{{ old('country') }}"
                                    class="form-control @error('country') is-invalid @enderror"
                                    placeholder="United States">
                                @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- 3. Payment Method --}}
                    <div class="section-card">
                        <div class="section-title">
                            <span class="step-num">3</span>
                            Payment method
                        </div>

                        @error('payment_method')
                            <div class="alert alert-danger mb-3" style="font-size:13px;">{{ $message }}</div>
                        @enderror

                        <div class="payment-options">
                            <label class="pay-opt {{ old('payment_method','stripe')==='stripe' ? 'selected' : '' }}"
                                   onclick="switchPayment(this,'stripe')">
                                <input type="radio" name="payment_method" value="stripe"
                                    {{ old('payment_method','stripe')==='stripe' ? 'checked' : '' }}>
                                <div class="pay-icon stripe">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5">
                                        <rect x="2" y="5" width="20" height="14" rx="2"/>
                                        <path d="M2 10h20"/>
                                    </svg>
                                </div>
                                <div class="pay-text">
                                    <div class="pay-label">Credit card</div>
                                    <div class="pay-sub">Visa, MC, Amex</div>
                                </div>
                                <div class="pay-check"><div class="pay-check-inner"></div></div>
                            </label>

                            <label class="pay-opt {{ old('payment_method')==='cash' ? 'selected' : '' }}"
                                   onclick="switchPayment(this,'cash')">
                                <input type="radio" name="payment_method" value="cash"
                                    {{ old('payment_method')==='cash' ? 'checked' : '' }}>
                                <div class="pay-icon cash">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="#3B6D11" stroke-width="1.5">
                                        <rect x="2" y="6" width="20" height="12" rx="2"/>
                                        <circle cx="12" cy="12" r="3"/>
                                        <path d="M6 12h.01M18 12h.01"/>
                                    </svg>
                                </div>
                                <div class="pay-text">
                                    <div class="pay-label">Cash on delivery</div>
                                    <div class="pay-sub">Pay at the door</div>
                                </div>
                                <div class="pay-check"><div class="pay-check-inner"></div></div>
                            </label>
                        </div>

                        {{-- Stripe panel --}}
                        <div class="pay-panel {{ old('payment_method','stripe')==='stripe' ? 'active' : '' }}" id="panel-stripe">
                            <div class="pay-panel-inner">
                                <div class="stripe-panel-header">
                                    <span class="stripe-panel-title">Pay securely with Stripe</span>
                                    <span class="stripe-badge">
                                        <svg viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect width="28" height="28" rx="6" fill="#fff" fill-opacity="0.15"/>
                                            <path d="M13.37 11.16c0-.74.61-1.03 1.62-1.03 1.44 0 2.27.39 3.01.76V7.57A8.85 8.85 0 0 0 15 7c-3.19 0-5.31 1.67-5.31 4.45 0 4.34 5.97 3.65 5.97 5.52 0 .87-.76 1.15-1.82 1.15-1.57 0-3.58-.65-3.58-.65v3.32c1.2.52 2.41.74 3.58.74 3.26 0 5.5-1.62 5.5-4.43-.02-4.68-6.97-3.85-6.97-5.94z" fill="#fff"/>
                                        </svg>
                                        Powered by Stripe
                                    </span>
                                </div>
                                <p class="stripe-panel-desc">
                                    After placing your order you'll be redirected to a secure Stripe-hosted
                                    payment page. Your card details are never stored on our servers.
                                </p>
                                <div class="card-brands">
                                    <span class="brand-pill brand-visa">VISA</span>
                                    <span class="brand-pill brand-mc">
                                        <span class="mc-circles">
                                            <span class="mc-circle mc-circle-red"></span>
                                            <span class="mc-circle mc-circle-orange"></span>
                                        </span>
                                        Mastercard
                                    </span>
                                    <span class="brand-pill brand-amex">AMEX</span>
                                </div>
                                <div class="secure-line">
                                    <svg width="12" height="12" viewBox="0 0 16 16" fill="none"
                                         stroke="#3B6D11" stroke-width="1.8">
                                        <rect x="3" y="7" width="10" height="8" rx="1.5"/>
                                        <path d="M5 7V5a3 3 0 016 0v2"/>
                                    </svg>
                                    256-bit SSL encryption · PCI DSS compliant
                                </div>
                            </div>
                        </div>

                        {{-- Cash panel --}}
                        <div class="pay-panel {{ old('payment_method')==='cash' ? 'active' : '' }}" id="panel-cash">
                            <div class="pay-panel-inner">
                                <div class="cash-note">
                                    <div class="cash-note-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="#3B6D11" stroke-width="1.5">
                                            <rect x="1" y="3" width="15" height="13" rx="2"/>
                                            <path d="M16 8h4a2 2 0 012 2v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-1"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="cash-note-title">Cash on delivery</div>
                                        <div class="cash-note-sub">Pay in cash when your order arrives. No card required.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- end left --}}

                {{-- RIGHT COLUMN --}}
                <div>
                    <div class="cart-card">
                        <div class="cart-header">
                            Your order
                            <span class="cart-count-badge">{{ $cartItems->count() }} items</span>
                        </div>
                        <div class="cart-items-list">
                            @foreach ($cartItems as $item)
                                <div class="cart-item">
                                    <div>
                                        <div class="item-name">{{ $item->product->name }}</div>
                                        <div class="item-qty">Qty {{ $item->quantity }}</div>
                                    </div>
                                    <div class="item-price">
                                        ${{ number_format($item->product->price * $item->quantity, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="cart-footer">
                            <div class="cart-summary-row">
                                <span>Subtotal</span>
                                <span class="val">${{ number_format($total, 2) }}</span>
                            </div>
                            <div class="cart-summary-row">
                                <span>Shipping</span>
                                <span class="free-shipping">Free</span>
                            </div>
                            <div class="cart-total-row">
                                <span>Total</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                            <button type="submit" class="btn-place-order" id="submit-btn">
                                Place order
                            </button>
                            <div class="ssl-note">
                                <svg width="11" height="11" viewBox="0 0 16 16" fill="none"
                                     stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="7" width="10" height="8" rx="1.5"/>
                                    <path d="M5 7V5a3 3 0 016 0v2"/>
                                </svg>
                                Secure checkout · No hidden fees
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchPayment(el, method) {
        document.querySelectorAll('.pay-opt').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
        document.querySelectorAll('.pay-panel').forEach(p => p.classList.remove('active'));
        var t = document.getElementById('panel-' + method);
        if (t) t.classList.add('active');
    }
</script>
@endpush