@extends('admin.layouts.master')

@section('title', 'Order ' . $order->order_number)

@push('styles')
<style>
.order-show-page { font-family: 'Public Sans', sans-serif; }

.page-header-card {
    background: linear-gradient(135deg, #696cff 0%, #9155fd 100%);
    border-radius: .5rem; padding: 1.5rem 1.75rem; margin-bottom: 1.5rem;
    position: relative; overflow: hidden;
    box-shadow: 0 4px 18px rgba(105,108,255,.35);
}
.page-header-card::before {
    content:''; position:absolute; top:-40px; right:-30px;
    width:160px; height:160px; border-radius:50%; background:rgba(255,255,255,.07);
}
.page-header-card::after {
    content:''; position:absolute; bottom:-50px; right:60px;
    width:100px; height:100px; border-radius:50%; background:rgba(255,255,255,.05);
}
.page-header-card .page-title { font-size:1.375rem; font-weight:600; color:#fff; margin:0 0 .25rem; }
.page-header-card .page-breadcrumb { font-size:.8125rem; color:rgba(255,255,255,.75); margin:0; }
.page-header-card .page-breadcrumb a { color:rgba(255,255,255,.85); text-decoration:none; }
.page-header-card .page-breadcrumb a:hover { color:#fff; }
.stat-chip {
    display:inline-flex; align-items:center; gap:.5rem;
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.2);
    border-radius:50px; padding:.3rem .875rem;
    font-size:.78rem; color:rgba(255,255,255,.9); font-weight:500;
    position:relative; z-index:1;
}
.stat-chip strong { color:#fff; }
.header-actions {
    display:flex; gap:.625rem; align-items:center; flex-wrap:wrap;
    position:relative; z-index:1;
}
.btn-header-ghost {
    display:inline-flex; align-items:center; gap:.375rem;
    font-size:.8125rem; font-weight:500; color:rgba(255,255,255,.9);
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25);
    border-radius:.375rem; padding:.4375rem 1rem; text-decoration:none; transition:all .2s;
}
.btn-header-ghost:hover { background:rgba(255,255,255,.25); color:#fff; transform:translateY(-1px); }

.info-card {
    background:#fff; border-radius:.5rem;
    border:1px solid rgba(75,70,92,.08);
    box-shadow:0 2px 6px rgba(75,70,92,.06);
    padding:1.5rem; margin-bottom:1.25rem;
}
.info-card-title {
    font-size:.6875rem; font-weight:700; letter-spacing:.08em;
    text-transform:uppercase; color:#a8aaae; margin-bottom:1.1rem;
    padding-bottom:.75rem; border-bottom:1px solid rgba(75,70,92,.07);
    display:flex; align-items:center; gap:.4rem;
}
.info-row {
    display:flex; justify-content:space-between;
    align-items:center; font-size:.875rem; margin-bottom:.6rem;
}
.info-label { color:#a8aaae; font-size:.8125rem; }
.info-value { font-weight:600; color:#4b465c; text-align:right; }

.items-table .item-row {
    display:flex; justify-content:space-between; align-items:center;
    padding:.75rem 0; border-bottom:1px solid rgba(75,70,92,.06); gap:1rem;
}
.items-table .item-row:last-child { border-bottom:none; }
.item-name { font-weight:600; color:#4b465c; font-size:.875rem; }
.item-qty  { font-size:.775rem; color:#a8aaae; margin-top:2px; }
.item-price{ font-weight:700; color:#4b465c; white-space:nowrap; }

.total-divider {
    display:flex; justify-content:space-between; align-items:center;
    padding-top:1rem; margin-top:.25rem;
    border-top:2px solid rgba(75,70,92,.1);
}
.total-label  { font-size:.9375rem; font-weight:700; color:#4b465c; }
.total-amount { font-size:1.25rem; font-weight:700; color:#696cff; }

.status-badge {
    display:inline-flex; align-items:center; gap:.3rem;
    font-size:.6875rem; font-weight:700; letter-spacing:.05em;
    text-transform:uppercase; border-radius:.25rem; padding:3px 10px;
}
.status-badge .dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }
.s-warning { background:rgba(255,159,67,.12);  color:#ff9f43; }
.s-warning .dot { background:#ff9f43; }
.s-success { background:rgba(40,199,111,.12);  color:#28c76f; }
.s-success .dot { background:#28c76f; }
.s-danger  { background:rgba(234,84,85,.12);   color:#ea5455; }
.s-danger .dot  { background:#ea5455; }
.s-info    { background:rgba(0,207,232,.12);   color:#00cfe8; }
.s-info .dot    { background:#00cfe8; }

.mat-select {
    width:100%; background:#fafafa; border:1px solid rgba(75,70,92,.12);
    border-radius:.375rem; color:#4b465c; font-family:'Public Sans',sans-serif;
    font-size:.875rem; padding:.4375rem .875rem; outline:none;
    appearance:none; cursor:pointer; transition:border-color .2s,box-shadow .2s;
}
.mat-select:focus { border-color:#696cff; box-shadow:0 0 0 3px rgba(105,108,255,.12); }
.select-wrap { position:relative; }
.select-wrap::after {
    content:'\ea4e'; font-family:'remixicon'; position:absolute;
    right:.75rem; top:50%; transform:translateY(-50%);
    color:#a8aaae; font-size:.875rem; pointer-events:none;
}
.form-label-sm { font-size:.75rem; font-weight:600; color:#4b465c; margin-bottom:.375rem; display:block; }
.btn-mat-primary {
    display:inline-flex; align-items:center; justify-content:center; gap:.375rem;
    font-family:'Public Sans',sans-serif; font-size:.8125rem; font-weight:600;
    color:#fff; background:#696cff; border:none; border-radius:.375rem;
    padding:.4375rem 1.25rem; cursor:pointer; transition:all .2s;
    box-shadow:0 2px 8px rgba(105,108,255,.35);
}
.btn-mat-primary:hover { background:#5f61e6; transform:translateY(-1px); }

.payment-chip {
    display:inline-flex; align-items:center; gap:.5rem;
    background:rgba(75,70,92,.06); border:1px solid rgba(75,70,92,.1);
    border-radius:.375rem; padding:.4rem 1rem;
    font-size:.875rem; font-weight:600; color:#4b465c;
}
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y order-show-page">

    {{-- Page Header --}}
    <div class="page-header-card">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="page-title">
                    <i class="ri ri-file-text-line me-2"></i>{{ $order->order_number }}
                </h4>
                <p class="page-breadcrumb mb-2">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    <a href="{{ route('admin.orders.index') }}">Orders</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    {{ $order->order_number }}
                </p>
                <div class="d-flex gap-2 flex-wrap mt-1">
                    <span class="stat-chip">
                        <i class="ri ri-calendar-line"></i>
                        {{ $order->created_at->format('M j, Y \a\t g:i A') }}
                    </span>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.orders.index') }}" class="btn-header-ghost">
                    <i class="ri ri-arrow-left-line"></i> Back to Orders
                </a>
            </div>
        </div>
    </div>

    <x-alert/>
    

    <div class="row">

        {{-- LEFT --}}
        <div class="col-lg-8">

            {{-- Items --}}
            <div class="info-card">
                <div class="info-card-title">
                    <i class="ri ri-shopping-cart-line"></i> Items Ordered
                </div>
                <div class="items-table">
                    @foreach($order->items as $item)
                        <div class="item-row">
                            <div>
                                <div class="item-name">{{ $item->product_name }}</div>
                                <div class="item-qty">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</div>
                            </div>
                            <div class="item-price">${{ number_format($item->price * $item->quantity, 2) }}</div>
                        </div>
                    @endforeach
                    <div class="total-divider">
                        <span class="total-label">Total</span>
                        <span class="total-amount">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Update Status --}}
            <div class="info-card">
                <div class="info-card-title">
                    <i class="ri ri-edit-2-line"></i> Update Order
                </div>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-sm">Order Status</label>
                            <div class="select-wrap">
                                <select name="status" class="mat-select">
                                    @foreach($orderStatuses as $s)
                                        <option value="{{ $s->value }}" @selected($order->status === $s)>
                                            {{ $s->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-sm">Payment Status</label>
                            <div class="select-wrap">
                                <select name="payment_status" class="mat-select">
                                    @foreach($paymentStatuses as $s)
                                        <option value="{{ $s->value }}" @selected($order->payment_status === $s)>
                                            {{ $s->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn-mat-primary">
                            <i class="ri ri-save-line"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">

            {{-- Status Overview --}}
            <div class="info-card">
                <div class="info-card-title">
                    <i class="ri ri-radar-line"></i> Status Overview
                </div>
                <div class="info-row">
                    <span class="info-label">Order Status</span>
                    @php $sc = 's-' . $order->status->color(); @endphp
                    <span class="status-badge {{ $sc }}">
                        <span class="dot"></span>
                        {{ $order->status->label() }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Status</span>
                    @php $pc = 's-' . $order->payment_status->color(); @endphp
                    <span class="status-badge {{ $pc }}">
                        <span class="dot"></span>
                        {{ $order->payment_status->label() }}
                    </span>
                </div>
            </div>

            {{-- Customer --}}
            <div class="info-card">
                <div class="info-card-title">
                    <i class="ri ri-user-line"></i> Customer
                </div>
                <div class="info-row">
                    <span class="info-label">Name</span>
                    <span class="info-value">{{ $order->first_name }} {{ $order->last_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value" style="font-size:.8rem">{{ $order->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone</span>
                    <span class="info-value">{{ $order->phone }}</span>
                </div>
            </div>

            {{-- Shipping --}}
            <div class="info-card">
                <div class="info-card-title">
                    <i class="ri ri-map-pin-line"></i> Shipping Address
                </div>
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

            {{-- Payment Method --}}
            <div class="info-card">
                <div class="info-card-title">
                    <i class="ri ri-bank-card-line"></i> Payment Method
                </div>
                <span class="payment-chip">
                    @if($order->payment_method === 'cash') 💵 Cash on Delivery
                    @elseif($order->payment_method === 'card') 💳 Credit Card
                    @else {{ ucfirst($order->payment_method) }}
                    @endif
                </span>
            </div>

        </div>
    </div>

</div>
@endsection