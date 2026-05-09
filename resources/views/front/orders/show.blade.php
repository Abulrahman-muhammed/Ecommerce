@extends('front.layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')

<style>
    .order-page {
        background: #f8f7f4;
        min-height: 100vh;
        padding: 3rem 0;
        font-family: 'Georgia', serif;
    }

    .order-header {
        background: #1a1a2e;
        color: #fff;
        border-radius: 16px;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .order-number {
        font-size: 0.85rem;
        color: #c8c8e0;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 0.3rem;
    }

    .order-title {
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.02em;
        color: #ffffff;
    }

    .order-date {
        font-size: 0.85rem;
        color: #c8c8e0;
        margin-top: 0.3rem;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .status-pending   { background: #fff3cd; color: #856404; }
    .status-processing{ background: #cce5ff; color: #004085; }
    .status-shipped   { background: #d4edda; color: #155724; }
    .status-delivered { background: #d1e7dd; color: #0f5132; }
    .status-cancelled { background: #f8d7da; color: #721c24; }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    /* Cards */
    .info-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.8rem 2rem;
        margin-bottom: 1.5rem;
        border: 1px solid #ece9e0;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .info-card h5 {
        font-size: 0.75rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #999;
        margin-bottom: 1.2rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #f0ede6;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.6rem;
        font-size: 0.9rem;
    }

    .info-label {
        color: #888;
        min-width: 120px;
    }

    .info-value {
        color: #1a1a2e;
        font-weight: 500;
        text-align: right;
    }

    /* Order Items */
    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #f5f3ee;
        gap: 1rem;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .item-name {
        font-weight: 600;
        color: #1a1a2e;
        font-size: 0.95rem;
    }

    .item-qty {
        font-size: 0.8rem;
        color: #999;
        margin-top: 0.2rem;
    }

    .item-price {
        font-weight: 700;
        color: #1a1a2e;
        font-size: 1rem;
        white-space: nowrap;
    }

    /* Total */
    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.2rem;
        border-top: 2px solid #1a1a2e;
        margin-top: 0.5rem;
    }

    .total-label {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a2e;
    }

    .total-amount {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a1a2e;
    }

    /* Payment Badge */
    .payment-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #f0ede6;
        border-radius: 8px;
        padding: 0.4rem 0.9rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: #444;
    }

    /* Success banner */
    .success-banner {
        background: linear-gradient(135deg, #d1e7dd, #a8dab5);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        color: #0f5132;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #1a1a2e;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 0.7rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        transition: opacity 0.2s;
    }

    .btn-back:hover {
        opacity: 0.85;
        color: #fff;
    }
</style>

<div class="order-page">
    <div class="container" style="max-width: 860px;">

        {{-- Success Message --}}
        @if (session('success'))
            <div class="success-banner">
                <span style="font-size: 1.2rem;">✓</span>
                {{ session('success') }}
            </div>
        @endif

        {{-- Order Header --}}
        <div class="order-header">
            <div>
                <div class="order-number">Order Number</div>
                <h1 class="order-title">{{ $order->order_number }}</h1>
                <div class="order-date">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</div>
            </div>

            <div class="d-flex flex-column align-items-end gap-2">
                {{-- Order Status --}}
                @php
                    $statusClass = match($order->status->label()) {
                        'pending'    => 'status-pending',
                        'processing' => 'status-processing',
                        'shipped'    => 'status-shipped',
                        'delivered'  => 'status-delivered',
                        'cancelled'  => 'status-cancelled',
                        default      => 'status-pending',
                    };
                @endphp
                <span class="status-badge {{ $statusClass }}">
                    <span class="status-dot"></span>
                    {{ $order->status->label()}}
                </span>

                {{-- Payment Status --}}
                @php
                    $payClass = match($order->payment_status->value) {
                        'paid'    => 'status-delivered',
                        'pending' => 'status-pending',
                        'failed'  => 'status-cancelled',
                        default   => 'status-pending',
                    };
                @endphp
                <span class="status-badge {{ $payClass }}" style="font-size: 0.72rem;">
                    Payment: {{ ucfirst($order->payment_status->label()) }}
                </span>
            </div>
        </div>

        <div class="row">

            {{-- LEFT --}}
            <div class="col-lg-7">

                {{-- Order Items --}}
                <div class="info-card">
                    <h5>Items Ordered</h5>

                    @foreach ($order->items as $item)
                        <div class="order-item">
                            <div>
                                <div class="item-name">{{ $item->product_name }}</div>
                                <div class="item-qty">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</div>
                            </div>
                            <div class="item-price">${{ number_format($item->price * $item->quantity, 2) }}</div>
                        </div>
                    @endforeach

                    <div class="total-row">
                        <span class="total-label">Total</span>
                        <span class="total-amount">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>

                {{-- Payment Info --}}
                <div class="info-card">
                    <h5>Payment</h5>
                    <div class="payment-badge">
                        @if($order->payment_method === 'cash')
                            💵 Cash on Delivery
                        @elseif($order->payment_method === 'card')
                            💳 Credit Card
                        @else
                            {{ ucfirst($order->payment_method) }}
                        @endif
                    </div>
                </div>

            </div>

            {{-- RIGHT --}}
            <div class="col-lg-5">

                {{-- Customer Info --}}
                <div class="info-card">
                    <h5>Customer</h5>
                    <div class="info-row">
                        <span class="info-label">Name</span>
                        <span class="info-value">{{ $order->first_name }} {{ $order->last_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $order->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone</span>
                        <span class="info-value">{{ $order->phone }}</span>
                    </div>
                </div>

                {{-- Shipping Address --}}
                <div class="info-card">
                    <h5>Shipping Address</h5>
                    <div class="info-row">
                        <span class="info-label">Address</span>
                        <span class="info-value">{{ $order->address }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">City</span>
                        <span class="info-value">{{ $order->city }}</span>
                    </div>
                    @if($order->state)
                    <div class="info-row">
                        <span class="info-label">State</span>
                        <span class="info-value">{{ $order->state }}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label">Postal Code</span>
                        <span class="info-value">{{ $order->postal_code }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Country</span>
                        <span class="info-value">{{ $order->country }}</span>
                    </div>
                </div>

                {{-- Back Button --}}
                <a href="{{ route('home') }}" class="btn-back">
                    ← Continue Shopping
                </a>

            </div>
        </div>

    </div>
</div>

@endsection