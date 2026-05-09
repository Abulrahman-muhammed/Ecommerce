@extends('admin.layouts.master')

@section('title', 'Customer Profile')

@push('styles')
<style>
    :root {
        --accent:      #7367f0;
        --accent-soft: rgba(115,103,240,.12);
        --danger:      #ea5455;
        --success:     #28c76f;
        --warning:     #ff9f43;
        --info:        #00cfe8;
        --text-primary:#4b465c;
        --text-muted:  #a5a3ae;
        --border:      #dbdade;
        --bg-body:     #f8f7fa;
        --card-bg:     #ffffff;
        --radius:      0.5rem;
        --shadow:      0 0.25rem 1.125rem rgba(161,172,184,.42);
    }

    /* ── Page header ── */
    .page-header { display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem; }
    .page-header h4 { font-size:1.125rem;font-weight:600;color:var(--text-primary);margin:0; }
    .btn-back {
        display:inline-flex;align-items:center;gap:.35rem;
        font-size:.8125rem;font-weight:500;color:var(--text-primary);
        background:var(--card-bg);border:1px solid var(--border);
        border-radius:var(--radius);padding:.4rem .9rem;
        text-decoration:none;transition:border-color .18s,color .18s,box-shadow .18s;
    }
    .btn-back:hover { border-color:var(--accent);color:var(--accent);box-shadow:0 2px 8px var(--accent-soft); }

    /* ══════════════════════════════════════════════════
       Profile card
       Structure:  card > banner (fixed height, no overflow:hidden)
                        > avatar-wrap (negative margin-top to overlap)
                        > identity (name, email, badges)
    ══════════════════════════════════════════════════ */
    .profile-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: visible;           /* avatar must NOT be clipped */
        margin-bottom: 1.5rem;
    }

    /* Coloured top banner — no overflow:hidden on this element */
    .profile-banner {
        background: linear-gradient(135deg, #696cff 0%, #9155fd 100%);
        border-radius: var(--radius) var(--radius) 0 0;
        height: 130px;
        position: relative;
    }
    .profile-banner::before {
        content:''; position:absolute; top:-30px; right:-20px;
        width:150px; height:150px; border-radius:50%;
        background:rgba(255,255,255,.08); pointer-events:none;
    }
    .profile-banner::after {
        content:''; position:absolute; bottom:-35px; left:25px;
        width:85px; height:85px; border-radius:50%;
        background:rgba(255,255,255,.05); pointer-events:none;
    }

    /* Avatar — pulls itself up with negative margin */
    .profile-avatar-wrap {
        display: flex;
        justify-content: center;
        margin-top: -56px;   /* half of avatar height (112px / 2) */
        position: relative;
        z-index: 5;
    }
    .profile-avatar {
        width: 112px; height: 112px;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 6px 24px rgba(105,108,255,.28);
        overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #696cff, #9155fd);
        font-size: 2.75rem; font-weight: 700; color: #fff;
        flex-shrink: 0;
    }
    .profile-avatar img { width:100%; height:100%; object-fit:cover; }

    /* Name / email / pill-badges */
    .profile-identity {
        text-align: center;
        padding: .875rem 1.5rem 1.5rem;
    }
    .p-name {
        font-size: 1.2rem; font-weight: 700;
        color: var(--text-primary); margin: 0 0 .2rem;
    }
    .p-email {
        font-size: .875rem; color: var(--text-muted); margin: 0 0 .875rem;
    }
    .profile-badges { display:flex; justify-content:center; gap:.5rem; flex-wrap:wrap; }
    .p-badge {
        display:inline-flex; align-items:center; gap:.3rem;
        font-size:.6875rem; font-weight:700; letter-spacing:.04em; text-transform:uppercase;
        border-radius:2rem; padding:4px 13px;
    }
    .p-badge.verified   { background:rgba(40,199,111,.1);  color:#28c76f; border:1px solid rgba(40,199,111,.25); }
    .p-badge.unverified { background:rgba(234,84,85,.1);   color:#ea5455; border:1px solid rgba(234,84,85,.25); }
    .p-badge.neutral    { background:rgba(75,70,92,.07);   color:#6d6d6d; border:1px solid rgba(75,70,92,.15); }
    .p-badge.google     { background:rgba(234,67,53,.07);  color:#ea4335; border:1px solid rgba(234,67,53,.2); }
    .p-badge.facebook   { background:rgba(24,119,242,.07); color:#1877f2; border:1px solid rgba(24,119,242,.2); }

    /* ── Stat row ── */
    .stat-row { display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
    .stat-card {
        flex:1; min-width:160px;
        background:var(--card-bg); border:1px solid var(--border);
        border-radius:var(--radius); box-shadow:var(--shadow);
        padding:1rem 1.25rem; display:flex; align-items:center; gap:.875rem;
    }
    .stat-icon {
        width:44px; height:44px; border-radius:.5rem;
        display:flex; align-items:center; justify-content:center;
        font-size:1.25rem; flex-shrink:0;
    }
    .stat-icon.purple { background:rgba(105,108,255,.1); color:#696cff; }
    .stat-icon.green  { background:rgba(40,199,111,.1);  color:#28c76f; }
    .stat-icon.orange { background:rgba(255,159,67,.1);  color:#ff9f43; }
    .stat-value { font-size:1.125rem; font-weight:700; color:var(--text-primary); display:block; }
    .stat-label { font-size:.75rem; color:var(--text-muted); display:block; margin-top:1px; }

    /* ── Action buttons ── */
    .profile-actions { display:flex; gap:.75rem; flex-wrap:wrap; margin-bottom:1.5rem; }
    .btn-action {
        display:inline-flex; align-items:center; gap:.4rem;
        padding:.5rem 1.125rem; font-size:.875rem; font-weight:500;
        border-radius:var(--radius); text-decoration:none;
        transition:all .18s; border:1px solid transparent; cursor:pointer;
        background: none;
    }
    .btn-action.edit {
        color:#fff; background:var(--accent); border-color:var(--accent);
        box-shadow:0 3px 10px rgba(115,103,240,.35);
    }
    .btn-action.edit:hover { background:#6254e8; box-shadow:0 4px 14px rgba(115,103,240,.45); }
    .btn-action.trash { color:var(--danger); background:#fff; border-color:rgba(234,84,85,.25); }
    .btn-action.trash:hover { background:rgba(234,84,85,.06); border-color:var(--danger); }

    /* ── Detail cards ── */
    .detail-card {
        background:var(--card-bg); border:1px solid var(--border);
        border-radius:var(--radius); box-shadow:var(--shadow);
        overflow:hidden; margin-bottom:1.5rem;
    }
    .detail-card-header {
        background:var(--bg-body); border-bottom:1px solid var(--border);
        padding:.875rem 1.25rem; display:flex; align-items:center; gap:.6rem;
    }
    .detail-card-header .hdr-icon {
        width:28px; height:28px; border-radius:.375rem;
        background:var(--accent-soft); color:var(--accent);
        display:flex; align-items:center; justify-content:center; font-size:.9rem;
    }
    .detail-card-header span { font-size:.85rem; font-weight:600; color:var(--text-primary); }
    .detail-row {
        display:flex; align-items:flex-start;
        padding:.875rem 1.25rem; border-bottom:1px solid rgba(75,70,92,.05);
        transition: background .15s;
    }
    .detail-row:last-child { border-bottom:none; }
    .detail-row:hover { background:rgba(105,108,255,.02); }
    .detail-key {
        width:155px; flex-shrink:0;
        font-size:.72rem; font-weight:700;
        color:var(--text-muted); text-transform:uppercase; letter-spacing:.06em;
        padding-top:.15rem;
    }
    .detail-val { flex:1; font-size:.875rem; color:var(--text-primary); }

    /* ── Inline badges ── */
    .bdg {
        display:inline-flex; align-items:center; gap:.3rem;
        font-size:.6875rem; font-weight:700; letter-spacing:.04em; text-transform:uppercase;
        border-radius:.25rem; padding:3px 9px;
    }
    .bdg-green  { background:rgba(40,199,111,.1);  color:#28c76f; border:1px solid rgba(40,199,111,.2); }
    .bdg-red    { background:rgba(234,84,85,.1);   color:#ea5455; border:1px solid rgba(234,84,85,.2); }
    .bdg-blue   { background:rgba(0,207,232,.1);   color:#00cfe8; border:1px solid rgba(0,207,232,.2); }
    .bdg-grey   { background:rgba(168,170,174,.1); color:#a8aaae; border:1px solid rgba(168,170,174,.2); }
    .bdg-google { background:rgba(234,67,53,.07);  color:#ea4335; border:1px solid rgba(234,67,53,.2); }
    .bdg-fb     { background:rgba(24,119,242,.07); color:#1877f2; border:1px solid rgba(24,119,242,.2); }

    /* ── Orders table card ── */
    .orders-card {
        background: var(--card-bg); border: 1px solid var(--border);
        border-radius: var(--radius); box-shadow: var(--shadow);
        overflow: hidden; margin-bottom: 1.5rem;
    }
    .orders-card-header {
        background: var(--bg-body); border-bottom: 1px solid var(--border);
        padding: .875rem 1.25rem;
        display: flex; align-items: center; justify-content: space-between;
    }
    .orders-card-header-left { display: flex; align-items: center; gap: .6rem; }
    .orders-card-header-left .hdr-icon {
        width: 28px; height: 28px; border-radius: .375rem;
        background: var(--accent-soft); color: var(--accent);
        display: flex; align-items: center; justify-content: center; font-size: .9rem;
    }
    .orders-card-header-left span { font-size: .85rem; font-weight: 600; color: var(--text-primary); }
    .orders-count-badge {
        display: inline-flex; align-items: center;
        background: rgba(105,108,255,.1); color: #696cff;
        border-radius: 50px; font-size: .6875rem; font-weight: 700;
        padding: 2px 10px; margin-left: .4rem;
    }
    .btn-view-all {
        display: inline-flex; align-items: center; gap: .3rem;
        font-size: .78rem; font-weight: 500; color: var(--accent);
        background: var(--accent-soft); border: 1px solid rgba(115,103,240,.2);
        border-radius: var(--radius); padding: .3rem .8rem;
        text-decoration: none; transition: background .18s;
    }
    .btn-view-all:hover { background: rgba(115,103,240,.2); color: var(--accent); }

    /* orders table */
    .orders-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
    .orders-table thead tr { background: #fafafa; border-bottom: 1px solid rgba(75,70,92,.08); }
    .orders-table th {
        font-size: .6875rem; font-weight: 700; letter-spacing: .07em;
        text-transform: uppercase; color: #a8aaae; padding: .75rem 1.25rem; white-space: nowrap;
    }
    .orders-table th.right, .orders-table td.right { text-align: right; }
    .orders-table th.center, .orders-table td.center { text-align: center; }
    .orders-table tbody tr { border-bottom: 1px solid rgba(75,70,92,.05); transition: background .15s; }
    .orders-table tbody tr:last-child { border-bottom: none; }
    .orders-table tbody tr:hover { background: rgba(105,108,255,.03); }
    .orders-table td { padding: .875rem 1.25rem; color: var(--text-primary); vertical-align: middle; }

    /* order id link */
    .order-id-link {
        font-size: .8125rem; font-weight: 600; color: var(--accent);
        text-decoration: none;
    }
    .order-id-link:hover { text-decoration: underline; }

    /* order total */
    .order-total { font-weight: 600; color: var(--text-primary); }

    /* order date */
    .order-date { font-size: .8rem; color: var(--text-muted); }

    /* order status badges */
    .o-badge {
        display: inline-flex; align-items: center; gap: .3rem;
        font-size: .6875rem; font-weight: 700; letter-spacing: .04em;
        text-transform: uppercase; border-radius: .25rem; padding: 3px 9px;
    }
    .o-pending   { background: rgba(255,159,67,.1);  color: #ff9f43; border: 1px solid rgba(255,159,67,.25); }
    .o-processing{ background: rgba(0,207,232,.1);   color: #00cfe8; border: 1px solid rgba(0,207,232,.25); }
    .o-completed { background: rgba(40,199,111,.1);  color: #28c76f; border: 1px solid rgba(40,199,111,.25); }
    .o-cancelled { background: rgba(234,84,85,.1);   color: #ea5455; border: 1px solid rgba(234,84,85,.25); }
    .o-refunded  { background: rgba(168,170,174,.1); color: #a8aaae; border: 1px solid rgba(168,170,174,.25); }
    .o-shipped   { background: rgba(105,108,255,.1); color: #696cff; border: 1px solid rgba(105,108,255,.25); }

    /* empty orders */
    .orders-empty { padding: 2.5rem 1.5rem; text-align: center; }
    .orders-empty-icon {
        width: 52px; height: 52px; border-radius: .5rem;
        background: rgba(105,108,255,.07);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto .875rem; font-size: 1.4rem; color: #696cff;
    }
    .orders-empty p { font-size: .875rem; color: var(--text-muted); margin: 0; }

    /* summary strip */
    .orders-summary {
        display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;
        padding: .75rem 1.25rem; border-top: 1px solid rgba(75,70,92,.07);
        background: #fafafa;
    }
    .orders-summary-item { font-size: .8rem; color: var(--text-muted); }
    .orders-summary-item strong { color: var(--text-primary); font-weight: 600; }

    html.swal2-shown, body.swal2-shown { padding-right:0!important; overflow:auto!important; }
    .swal2-container { z-index:9999!important; }
    .swal2-popup { font-family:'Public Sans',sans-serif!important; border-radius:.5rem!important; }
    .swal2-title { font-size:1.125rem!important; font-weight:600!important; color:#4b465c!important; }
    .swal2-html-container { font-size:.875rem!important; color:#a8aaae!important; }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Page Header --}}
    <div class="page-header">
        <h4>Customer Profile</h4>
        <a href="{{ route('admin.customers.index') }}" class="btn-back">
            <i class="ri ri-arrow-left-line"></i> Back to Customers
        </a>
    </div>

    {{-- ══ Profile Card ══ --}}
    <div class="profile-card">

        {{-- Banner (no overflow:hidden — just a coloured strip) --}}
        <div class="profile-banner"></div>

        {{-- Avatar: negative margin-top pulls it over the banner --}}
<div class="profile-avatar-wrap">
    <div class="profile-avatar">
        @if($customer->avatar)

            @if(filter_var($customer->avatar, FILTER_VALIDATE_URL))
                {{-- image from provider --}}
                <img src="{{ $customer->avatar }}"
                    alt="{{ $customer->name }}">
            @else
                {{-- image from storage --}}
                <img src="{{ asset('storage/'.$customer->avatar) }}"
                    alt="{{ $customer->name }}">
            @endif

        @else
            {{ strtoupper(substr($customer->name, 0, 1)) }}
        @endif
    </div>
</div>

        {{-- Name + email + quick-info badges --}}
        <div class="profile-identity">
            <p class="p-name">{{ $customer->name }}</p>
            <p class="p-email">{{ $customer->email }}</p>

            @php
                $provider = $customer->provider ?? 'local';
                $pBadgeClass = match($provider) {
                    'google'   => 'google',
                    'facebook' => 'facebook',
                    default    => 'neutral',
                };
                $pIcon = match($provider) {
                    'google'   => 'ri-google-line',
                    'facebook' => 'ri-facebook-circle-line',
                    default    => 'ri-lock-password-line',
                };
            @endphp

            <div class="profile-badges">
                @if($customer->email_verified_at)
                    <span class="p-badge verified">
                        <i class="ri ri-shield-check-line"></i> Verified
                    </span>
                @else
                    <span class="p-badge unverified">
                        <i class="ri ri-shield-cross-line"></i> Not Verified
                    </span>
                @endif

                <span class="p-badge {{ $pBadgeClass }}">
                    <i class="{{ $pIcon }}"></i> {{ ucfirst($provider) }}
                </span>

                <span class="p-badge neutral">
                    <i class="ri ri-fingerprint-line"></i> ID #{{ $customer->id }}
                </span>
            </div>
        </div>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="ri ri-shopping-bag-3-line"></i></div>
            <div>
                <span class="stat-value">{{ $customer->orders_count ?? 0 }}</span>
                <span class="stat-label">Total Orders</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="ri ri-wallet-3-line"></i></div>
            <div>
                <span class="stat-value">${{ number_format($customer->orders_sum_total ?? 0, 2) }}</span>
                <span class="stat-label">Total Spent</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="ri ri-calendar-check-line"></i></div>
            <div>
                <span class="stat-value">{{ $customer->created_at->format('M d, Y') }}</span>
                <span class="stat-label">Member Since</span>
            </div>
        </div>
    </div>

    {{-- ── Action Buttons ── --}}
    <div class="profile-actions">
        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn-action edit">
            <i class="ri ri-pencil-line"></i> Edit Customer
        </a>
        <form action="{{ route('admin.customers.destroy', $customer->id) }}"
              method="POST" class="delete-form m-0 p-0">
            @csrf @method('DELETE')
            <button type="button" class="btn-action trash delete-btn">
                <i class="ri ri-delete-bin-line"></i> Move to Trash
            </button>
        </form>
    </div>

    {{-- ── Detail Cards ── --}}
    <div class="row g-4">

        {{-- Personal Info --}}
        <div class="col-lg-6">
            <div class="detail-card">
                <div class="detail-card-header">
                    <div class="hdr-icon"><i class="ri ri-user-line"></i></div>
                    <span>Personal Information</span>
                </div>
                <div class="detail-card-body">
                    <div class="detail-row">
                        <div class="detail-key">Full Name</div>
                        <div class="detail-val"><strong>{{ $customer->name }}</strong></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Email</div>
                        <div class="detail-val">{{ $customer->email }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Phone</div>
                        <div class="detail-val">{{ $customer->phone ?: '—' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Address</div>
                        <div class="detail-val">{{ $customer->address ?: '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="col-lg-6">
            <div class="detail-card">
                <div class="detail-card-header">
                    <div class="hdr-icon"><i class="ri ri-shield-user-line"></i></div>
                    <span>Account Information</span>
                </div>
                <div class="detail-card-body">
                    <div class="detail-row">
                        <div class="detail-key">Customer ID</div>
                        <div class="detail-val">
                            <code style="background:rgba(75,70,92,.06);padding:2px 7px;border-radius:4px;font-size:.8rem;">
                                #{{ $customer->id }}
                            </code>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Email Status</div>
                        <div class="detail-val">
                            @if($customer->email_verified_at)
                                <span class="bdg bdg-green">
                                    <i class="ri ri-shield-check-line"></i> Verified
                                </span>
                                <small style="color:var(--text-muted);font-size:.73rem;margin-left:.5rem;">
                                    {{ $customer->email_verified_at->format('M d, Y') }}
                                </small>
                            @else
                                <span class="bdg bdg-red">
                                    <i class="ri ri-shield-cross-line"></i> Not Verified
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Provider</div>
                        <div class="detail-val">
                            @php
                                $bdgClass = match($provider) {
                                    'google'   => 'bdg-google',
                                    'facebook' => 'bdg-fb',
                                    default    => 'bdg-grey',
                                };
                            @endphp
                            <span class="bdg {{ $bdgClass }}">
                                <i class="{{ $pIcon }}"></i> {{ ucfirst($provider) }}
                            </span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Joined</div>
                        <div class="detail-val">{{ $customer->created_at->format('M d, Y · H:i') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Last Updated</div>
                        <div class="detail-val">{{ $customer->updated_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════
         Orders Section
    ══════════════════════════════════════ --}}
    <div class="orders-card">

        <div class="orders-card-header">
            <div class="orders-card-header-left">
                <div class="hdr-icon"><i class="ri ri-shopping-bag-3-line"></i></div>
                <span>
                    Order History
                    <span class="orders-count-badge">{{ $orders->total() }}</span>
                </span>
            </div>
            @if($orders->total() > 0)
                <a href="{{ route('admin.orders.index', ['customer_id' => $customer->id]) }}"
                   class="btn-view-all">
                    <i class="ri ri-external-link-line"></i> View All
                </a>
            @endif
        </div>

        <div style="overflow-x: auto;">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Items</th>
                        <th class="center">Status</th>
                        <th>Payment</th>
                        <th class="right">Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="order-id-link">
                                #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                            </a>
                        </td>
                        <td>
                            <span style="font-size:.8rem;color:var(--text-muted);">
                                {{ $order->items_count ?? $order->items->count() }}
                                {{ Str::plural('item', $order->items_count ?? $order->items->count()) }}
                            </span>
                        </td>
                        <td class="center">
                            @php
                                $statusClass = match(strtolower($order->status->label() ?? '')) {
                                    'pending'              => 'o-pending',
                                    'processing'           => 'o-processing',
                                    'shipped'              => 'o-shipped',
                                    'completed','delivered'=> 'o-completed',
                                    'cancelled','canceled' => 'o-cancelled',
                                    'refunded'             => 'o-refunded',
                                    default                => 'o-pending',
                                };
                                $statusIcon = match(strtolower($order->status->label()   ?? '')) {
                                    'pending'              => 'ri-time-line',
                                    'processing'           => 'ri-loader-4-line',
                                    'shipped'              => 'ri-truck-line',
                                    'completed','delivered'=> 'ri-checkbox-circle-line',
                                    'cancelled','canceled' => 'ri-close-circle-line',
                                    'refunded'             => 'ri-refund-2-line',
                                    default                => 'ri-time-line',
                                };
                            @endphp
                            <span class="o-badge {{ $statusClass }}">
                                <i class="{{ $statusIcon }}"></i>
                                {{ ucfirst($order->status->label() ?? 'pending') }}
                            </span>
                        </td>
                        <td>
                            @if($order->payment_method)
                                <span style="font-size:.8rem;color:var(--text-muted);display:flex;align-items:center;gap:.3rem;">
                                    <i class="ri ri-bank-card-line"></i>
                                    {{ ucfirst($order->payment_method) }}
                                </span>
                            @else
                                <span style="color:#c4c4c4;font-weight:700;">—</span>
                            @endif
                        </td>
                        <td class="right">
                            <span class="order-total">${{ number_format($order->total_amount ?? 0, 2) }}</span>
                        </td>
                        <td>
                            <div style="font-size:.8125rem;color:var(--text-primary);">
                                {{ $order->created_at->format('M d, Y') }}
                            </div>
                            <div class="order-date">{{ $order->created_at->diffForHumans() }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="orders-empty">
                                <div class="orders-empty-icon">
                                    <i class="ri ri-shopping-bag-3-line"></i>
                                </div>
                                <p>This customer hasn't placed any orders yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Summary strip --}}
        @if($orders->total() > 0)
        <div class="orders-summary">
            <div class="orders-summary-item">
                Total Orders: <strong>{{ $orders->total() }}</strong>
            </div>
            <div class="orders-summary-item">
                Total Spent: <strong>${{ number_format($orders->sum('total_amount'), 2) }}</strong>
            </div>
            <div class="orders-summary-item">
                Avg Order:
                <strong>${{ number_format($orders->total() > 0 ? $orders->sum('total_amount') / $orders->total() : 0, 2) }}</strong>
            </div>
            @if($orders->hasPages())
                <div class="ms-auto">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
        @endif

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const form = this.closest('.delete-form');
        Swal.fire({
            title: 'Move to Trash?',
            html: `<p style="color:#a8aaae;font-size:.875rem;margin:0">
                       This customer will be moved to the
                       <strong style="color:#696cff">Trash</strong>.
                       You can restore them anytime.
                   </p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ri ri-delete-bin-line me-1"></i> Yes, Move to Trash',
            cancelButtonText: 'Cancel',
            backdrop: true,
            allowOutsideClick: false,
            scrollbarPadding: false,
            didOpen: () => { document.body.style.paddingRight = '0px'; },
            customClass: {
                confirmButton: 'btn btn-danger me-2 waves-effect waves-light',
                cancelButton:  'btn btn-label-secondary waves-effect'
            },
            buttonsStyling: false
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush