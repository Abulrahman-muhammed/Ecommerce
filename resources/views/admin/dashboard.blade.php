@extends('admin.layouts.master')

@section('title', config('app.name') . ' - Dashboard')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:root {
    --accent:        #7367f0;
    --accent-2:      #9155fd;
    --success:       #28c76f;
    --warning:       #ff9f43;
    --danger:        #ea5455;
    --info:          #00cfe8;
    --text:          #4b465c;
    --text-muted:    #a5a3ae;
    --border:        rgba(75,70,92,.1);
    --card-bg:       #ffffff;
    --page-bg:       #f8f7fa;
    --radius:        .75rem;
    --shadow-sm:     0 2px 8px rgba(75,70,92,.08);
    --shadow-md:     0 4px 20px rgba(75,70,92,.12);
    --shadow-accent: 0 6px 24px rgba(115,103,240,.3);
}

.dash { font-family:'Plus Jakarta Sans',sans-serif; }

/* ── Stat Cards ── */
.stat-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.1rem;
    transition: transform .2s, box-shadow .2s;
    text-decoration: none;
    color: inherit;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
    color: inherit;
}
.stat-icon {
    width: 52px; height: 52px;
    border-radius: .625rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
}
.stat-icon.c-primary   { background: rgba(115,103,240,.12); color: var(--accent); }
.stat-icon.c-success   { background: rgba(40,199,111,.12);  color: var(--success); }
.stat-icon.c-warning   { background: rgba(255,159,67,.12);  color: var(--warning); }
.stat-icon.c-danger    { background: rgba(234,84,85,.12);   color: var(--danger); }
.stat-icon.c-info      { background: rgba(0,207,232,.12);   color: var(--info); }
.stat-icon.c-secondary { background: rgba(168,170,174,.12); color: #a8aaae; }

.stat-label { font-size: .78rem; font-weight: 500; color: var(--text-muted); margin: 0 0 .2rem; }
.stat-value { font-size: 1.6rem; font-weight: 800; color: var(--text); margin: 0; line-height: 1; }
.stat-sub   { font-size: .74rem; color: var(--text-muted); margin: .3rem 0 0; }
.stat-sub .up   { color: var(--success); font-weight: 600; }
.stat-sub .down { color: var(--danger);  font-weight: 600; }

/* ── Section title ── */
.section-title {
    font-size: .65rem; font-weight: 700; letter-spacing: .1em;
    text-transform: uppercase; color: var(--text-muted);
    margin: 0 0 1rem; display: flex; align-items: center; gap: .5rem;
}
.section-title::after { content:''; flex:1; height:1px; background:var(--border); }

/* ── Generic card ── */
.d-card {
    background: var(--card-bg); border-radius: var(--radius);
    border: 1px solid var(--border); box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.d-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
}
.d-card-title { font-size: .9rem; font-weight: 700; color: var(--text); margin: 0; }
.d-card-body  { padding: 1.25rem; }

/* ── Order status pills ── */
.pill {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .68rem; font-weight: 700; letter-spacing: .05em;
    text-transform: uppercase; border-radius: 50px; padding: 3px 10px;
}
.pill-pending  { background: rgba(255,159,67,.12);  color: var(--warning); }
.pill-success  { background: rgba(40,199,111,.12);  color: var(--success); }
.pill-danger   { background: rgba(234,84,85,.12);   color: var(--danger); }
.pill-info     { background: rgba(0,207,232,.12);   color: var(--info); }
.pill-secondary{ background: rgba(168,170,174,.12); color: #a8aaae; }
.pill .dot     { width:5px; height:5px; border-radius:50%; background:currentColor; }

/* ── Mini progress ── */
.mini-bar { height: 5px; border-radius: 50px; background: rgba(75,70,92,.08); overflow: hidden; margin-top:.4rem; }
.mini-bar-fill { height:100%; border-radius:50px; }

/* ── Recent orders table ── */
.r-table { width:100%; border-collapse:collapse; font-size:.84rem; }
.r-table thead tr { border-bottom:1px solid var(--border); }
.r-table th { font-size:.65rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--text-muted); padding:.625rem 1rem; white-space:nowrap; }
.r-table tbody tr { border-bottom:1px solid rgba(75,70,92,.05); transition:background .15s; }
.r-table tbody tr:last-child { border-bottom:none; }
.r-table tbody tr:hover { background:rgba(115,103,240,.03); }
.r-table td { padding:.75rem 1rem; color:var(--text); vertical-align:middle; }
.r-table td.center { text-align:center; }

/* ── Top products ── */
.product-row {
    display:flex; align-items:center; gap:.875rem;
    padding:.625rem 0; border-bottom:1px solid rgba(75,70,92,.05);
}
.product-row:last-child { border-bottom:none; }
.product-rank {
    width:22px; height:22px; border-radius:50%; background:var(--accent); color:#fff;
    font-size:.65rem; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0;
}
.product-rank.r2 { background:var(--success); }
.product-rank.r3 { background:var(--warning); }
.product-rank.other { background:rgba(75,70,92,.12); color:var(--text-muted); }
.product-name { font-size:.84rem; font-weight:600; color:var(--text); }
.product-meta { font-size:.73rem; color:var(--text-muted); }
.product-sales { font-size:.84rem; font-weight:700; color:var(--text); margin-left:auto; flex-shrink:0; }

/* ── Category distribution ── */
.cat-bar-row { margin-bottom:.875rem; }
.cat-bar-label { display:flex; justify-content:space-between; margin-bottom:.3rem; }
.cat-bar-name  { font-size:.8rem; font-weight:600; color:var(--text); }
.cat-bar-pct   { font-size:.78rem; font-weight:700; color:var(--text-muted); }

/* ── Activity feed ── */
.activity-item { display:flex; gap:.875rem; padding:.625rem 0; border-bottom:1px solid rgba(75,70,92,.05); }
.activity-item:last-child { border-bottom:none; }
.activity-dot {
    width: 8px; height:8px; border-radius:50%; flex-shrink:0; margin-top:.35rem;
}
.activity-text { font-size:.82rem; color:var(--text); line-height:1.5; }
.activity-time { font-size:.72rem; color:var(--text-muted); margin-top:.2rem; }

/* ── Gradient header ── */
.dash-header {
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%);
    border-radius: var(--radius);
    padding: 1.5rem 1.75rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-accent);
    position: relative;
    overflow: hidden;
}
.dash-header::before {
    content:''; position:absolute; top:-40px; right:-30px;
    width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,.07);
}
.dash-header::after {
    content:''; position:absolute; bottom:-50px; right:80px;
    width:120px; height:120px; border-radius:50%; background:rgba(255,255,255,.05);
}
.dash-header h4 { font-size:1.25rem; font-weight:800; color:#fff; margin:0 0 .25rem; position:relative; z-index:1; }
.dash-header p  { font-size:.84rem; color:rgba(255,255,255,.8); margin:0; position:relative; z-index:1; }
.dash-header-badges { display:flex; gap:.5rem; flex-wrap:wrap; margin-top:.875rem; position:relative; z-index:1; }
.dash-badge {
    display:inline-flex; align-items:center; gap:.375rem;
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25);
    border-radius:50px; padding:.3rem .875rem; font-size:.75rem;
    color:rgba(255,255,255,.95); font-weight:600; backdrop-filter:blur(4px);
}
.dash-badge strong { color:#fff; }

/* ── View all link ── */
.view-all {
    font-size:.78rem; font-weight:600; color:var(--accent); text-decoration:none;
    display:inline-flex; align-items:center; gap:.25rem;
}
.view-all:hover { color:var(--accent-2); }

/* entry animations */
@keyframes fadeUp {
    from { opacity:0; transform:translateY(12px); }
    to   { opacity:1; transform:translateY(0); }
}
.stat-card { animation: fadeUp .35s ease both; }
.stat-card:nth-child(1) { animation-delay:.05s; }
.stat-card:nth-child(2) { animation-delay:.10s; }
.stat-card:nth-child(3) { animation-delay:.15s; }
.stat-card:nth-child(4) { animation-delay:.20s; }
.stat-card:nth-child(5) { animation-delay:.25s; }
.stat-card:nth-child(6) { animation-delay:.30s; }
</style>
@endpush

@section('content')

<div class="container-xxl flex-grow-1 container-p-y dash">

    {{-- ── Header ── --}}
    <div class="dash-header">
        <h4>📊 Dashboard Overview</h4>
        <p>Welcome back! Here's what's happening with your store today.</p>
        <div class="dash-header-badges">
            <span class="dash-badge"><i class="ri ri-shopping-bag-line"></i> Today's Orders: <strong>{{ $todayOrders }}</strong></span>
            <span class="dash-badge"><i class="ri ri-money-dollar-circle-line"></i> This Month: <strong>${{ number_format($monthRevenue, 0) }}</strong></span>
            <span class="dash-badge"><i class="ri ri-time-line"></i> {{ now()->format('D, d M Y') }}</span>
        </div>
    </div>

    {{-- ── Stat Cards Row 1 ── --}}
    <div class="section-title">Core Metrics</div>
    <div class="row g-3 mb-4">

        {{-- Total Revenue --}}
        <div class="col-xl-2 col-md-4 col-6">
            <div class="stat-card">
                <div class="stat-icon c-primary"><i class="ri ri-money-dollar-circle-line"></i></div>
                <div>
                    <p class="stat-label">Total Revenue</p>
                    <h4 class="stat-value">${{ number_format($totalRevenue, 0) }}</h4>
                    <p class="stat-sub">
                        @if($revGrowth >= 0)
                            <span class="up"><i class="ri ri-arrow-up-s-line"></i>{{ $revGrowth }}%</span>
                        @else
                            <span class="down"><i class="ri ri-arrow-down-s-line"></i>{{ abs($revGrowth) }}%</span>
                        @endif
                        vs last month
                    </p>
                </div>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="col-xl-2 col-md-4 col-6">
            <a href="{{ route('admin.orders.index') }}" class="stat-card">
                <div class="stat-icon c-success"><i class="ri ri-shopping-bag-3-line"></i></div>
                <div>
                    <p class="stat-label">Total Orders</p>
                    <h4 class="stat-value">{{ number_format($totalOrders) }}</h4>
                    <p class="stat-sub">
                        @if($orderGrowth >= 0)
                            <span class="up"><i class="ri ri-arrow-up-s-line"></i>{{ $orderGrowth }}%</span>
                        @else
                            <span class="down"><i class="ri ri-arrow-down-s-line"></i>{{ abs($orderGrowth) }}%</span>
                        @endif
                        vs last month
                    </p>
                </div>
            </a>
        </div>

        {{-- Customers --}}
        <div class="col-xl-2 col-md-4 col-6">
            <div class="stat-card">
                <div class="stat-icon c-info"><i class="ri ri-group-line"></i></div>
                <div>
                    <p class="stat-label">Customers</p>
                    <h4 class="stat-value">{{ number_format($totalUsers) }}</h4>
                    <p class="stat-sub"><span class="up">+{{ $newUsers }}</span> this month</p>
                </div>
            </div>
        </div>

        {{-- Products --}}
        <div class="col-xl-2 col-md-4 col-6">
            <a href="{{ route('admin.products.index') }}" class="stat-card">
                <div class="stat-icon c-warning"><i class="ri ri-store-2-line"></i></div>
                <div>
                    <p class="stat-label">Products</p>
                    <h4 class="stat-value">{{ number_format($totalProducts) }}</h4>
                    <p class="stat-sub"><span class="up">{{ $activeProducts }}</span> active</p>
                </div>
            </a>
        </div>

        {{-- Categories --}}
        <div class="col-xl-2 col-md-4 col-6">
            <a href="{{ route('admin.categories.index') }}" class="stat-card">
                <div class="stat-icon c-secondary"><i class="ri ri-layout-grid-line"></i></div>
                <div>
                    <p class="stat-label">Categories</p>
                    <h4 class="stat-value">{{ $totalCategories }}</h4>
                    <p class="stat-sub"><span class="up">{{ $featuredCats }}</span> featured</p>
                </div>
            </a>
        </div>

        {{-- Active Carts --}}
        <div class="col-xl-2 col-md-4 col-6">
            <div class="stat-card">
                <div class="stat-icon c-danger"><i class="ri ri-shopping-cart-2-line"></i></div>
                <div>
                    <p class="stat-label">Active Carts</p>
                    <h4 class="stat-value">{{ number_format($activeCarts) }}</h4>
                    <p class="stat-sub">{{ number_format($cartItems) }} items</p>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Order Status + Low Stock ── --}}
    <div class="row g-3 mb-4">

        {{-- Order Status Breakdown --}}
        <div class="col-xl-8">
            <div class="d-card h-100">
                <div class="d-card-header">
                    <span class="d-card-title"><i class="ri ri-bar-chart-box-line me-2 text-primary"></i>Order Status Breakdown</span>
                    <a href="{{ route('admin.orders.index') }}" class="view-all">View all <i class="ri ri-arrow-right-s-line"></i></a>
                </div>
                <div class="d-card-body">
                    <div class="row g-3">
                        @php
                        $statuses = [
                            ['label'=>'Pending',    'count'=>$pendingOrders,    'color'=>'#ff9f43', 'bg'=>'rgba(255,159,67,.12)',  'icon'=>'ri-time-line'],
                            ['label'=>'Processing', 'count'=>$processingOrders, 'color'=>'#00cfe8', 'bg'=>'rgba(0,207,232,.12)',   'icon'=>'ri-loader-line'],
                            ['label'=>'Shipped',    'count'=>$shippedOrders,    'color'=>'#7367f0', 'bg'=>'rgba(115,103,240,.12)', 'icon'=>'ri-truck-line'],
                            ['label'=>'Delivered',  'count'=>$deliveredOrders,  'color'=>'#28c76f', 'bg'=>'rgba(40,199,111,.12)',  'icon'=>'ri-checkbox-circle-line'],
                            ['label'=>'Cancelled',  'count'=>$cancelledOrders,  'color'=>'#ea5455', 'bg'=>'rgba(234,84,85,.12)',   'icon'=>'ri-close-circle-line'],
                        ];
                        @endphp
                        @foreach($statuses as $s)
                        <div class="col-md col-6">
                            <div style="background:{{ $s['bg'] }};border-radius:.625rem;padding:1rem;text-align:center;">
                                <div style="font-size:1.5rem;color:{{ $s['color'] }};margin-bottom:.5rem;">
                                    <i class="ri {{ $s['icon'] }}"></i>
                                </div>
                                <div style="font-size:1.5rem;font-weight:800;color:{{ $s['color'] }};line-height:1;">{{ number_format($s['count']) }}</div>
                                <div style="font-size:.75rem;font-weight:600;color:{{ $s['color'] }};margin-top:.25rem;opacity:.85;">{{ $s['label'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Progress bar --}}
                    @if($totalOrders > 0)
                    <div class="mt-4">
                        <div style="display:flex;height:10px;border-radius:50px;overflow:hidden;gap:2px;">
                            @foreach($statuses as $s)
                                @if($s['count'] > 0)
                                <div style="flex:{{ $s['count'] }};background:{{ $s['color'] }};border-radius:50px;" title="{{ $s['label'] }}: {{ $s['count'] }}"></div>
                                @endif
                            @endforeach
                        </div>
                        <div style="display:flex;gap:1.25rem;margin-top:.75rem;flex-wrap:wrap;">
                            @foreach($statuses as $s)
                            <span style="display:inline-flex;align-items:center;gap:.35rem;font-size:.73rem;color:#a5a3ae;font-weight:500;">
                                <span style="width:8px;height:8px;border-radius:50%;background:{{ $s['color'] }};display:inline-block;"></span>
                                {{ $s['label'] }} ({{ $totalOrders > 0 ? round($s['count']/$totalOrders*100) : 0 }}%)
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stock Alerts --}}
        <div class="col-xl-4">
            <div class="d-card h-100">
                <div class="d-card-header">
                    <span class="d-card-title"><i class="ri ri-alert-line me-2 text-warning"></i>Stock Alerts</span>
                </div>
                <div class="d-card-body">
                    <div class="d-flex align-items-center gap-3 mb-3 p-3" style="background:rgba(234,84,85,.07);border-radius:.625rem;border:1px solid rgba(234,84,85,.15);">
                        <div style="font-size:1.75rem;color:#ea5455;"><i class="ri ri-error-warning-line"></i></div>
                        <div>
                            <div style="font-size:1.4rem;font-weight:800;color:#ea5455;line-height:1;">{{ $outOfStock }}</div>
                            <div style="font-size:.78rem;color:#ea5455;font-weight:600;opacity:.85;">Out of Stock</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-3 p-3" style="background:rgba(255,159,67,.07);border-radius:.625rem;border:1px solid rgba(255,159,67,.15);">
                        <div style="font-size:1.75rem;color:#ff9f43;"><i class="ri ri-alarm-warning-line"></i></div>
                        <div>
                            <div style="font-size:1.4rem;font-weight:800;color:#ff9f43;line-height:1;">{{ $lowStock }}</div>
                            <div style="font-size:.78rem;color:#ff9f43;font-weight:600;opacity:.85;">Low Stock (≤5)</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3 p-3" style="background:rgba(40,199,111,.07);border-radius:.625rem;border:1px solid rgba(40,199,111,.15);">
                        <div style="font-size:1.75rem;color:#28c76f;"><i class="ri ri-checkbox-circle-line"></i></div>
                        <div>
                            <div style="font-size:1.4rem;font-weight:800;color:#28c76f;line-height:1;">{{ $activeProducts - $outOfStock - $lowStock }}</div>
                            <div style="font-size:.78rem;color:#28c76f;font-weight:600;opacity:.85;">Healthy Stock</div>
                        </div>
                    </div>

                    @if($outOfStock > 0 || $lowStock > 0)
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm w-100 mt-3"
                       style="background:rgba(115,103,240,.1);color:#7367f0;border:none;font-weight:600;font-size:.8rem;border-radius:.5rem;padding:.5rem;">
                        <i class="ri ri-external-link-line me-1"></i> Review Products
                    </a>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- ── Recent Orders + Top Products ── --}}
    <div class="row g-3 mb-4">

        {{-- Recent Orders --}}
        <div class="col-xl-8">
            <div class="d-card">
                <div class="d-card-header">
                    <span class="d-card-title"><i class="ri ri-receipt-line me-2 text-success"></i>Recent Orders</span>
                    <a href="{{ route('admin.orders.index') }}" class="view-all">View all <i class="ri ri-arrow-right-s-line"></i></a>
                </div>
                <div style="overflow-x:auto">
                    <table class="r-table">
                        <thead>
                            <tr>
                                <th>#Order</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th class="center">Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <span style="font-size:.78rem;font-weight:700;color:#7367f0;font-family:monospace;">
                                        #{{ $order->order_number }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight:600;font-size:.82rem;">{{ $order->first_name }} {{ $order->last_name }}</div>
                                    <div style="font-size:.72rem;color:#a5a3ae;">{{ $order->email }}</div>
                                </td>
                                <td style="font-weight:700;">${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    @php
                                        $pClass = match($order->payment_status->label() ?? '') {
                                            'paid'    => 'pill-success',
                                            'failed'  => 'pill-danger',
                                            'refunded'=> 'pill-info',
                                            default   => 'pill-pending',
                                        };
                                    @endphp
                                    <span class="pill {{ $pClass }}"><span class="dot"></span>{{ ucfirst($order->payment_status->label() ?? 'pending') }}</span>
                                </td>
                                <td class="center">
                                    @php
                                        $sClass = match($order->status->label() ?? '') {
                                            'delivered'  => 'pill-success',
                                            'shipped'    => 'pill-info',
                                            'processing' => 'pill-secondary',
                                            'cancelled'  => 'pill-danger',
                                            default      => 'pill-pending',
                                        };
                                    @endphp
                                    <span class="pill {{ $sClass }}"><span class="dot"></span>{{ ucfirst($order->status->label() ?? 'pending') }}</span>
                                </td>
                                <td style="font-size:.78rem;color:#a5a3ae;">{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center;padding:2rem;color:#a5a3ae;">No orders yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="col-xl-4">
            <div class="d-card h-100">
                <div class="d-card-header">
                    <span class="d-card-title"><i class="ri ri-trophy-line me-2 text-warning"></i>Top Selling Products</span>
                </div>
                <div class="d-card-body">
                    @forelse($topProducts as $i => $product)
                    <div class="product-row">
                        <div class="product-rank {{ $i === 0 ? '' : ($i === 1 ? 'r2' : ($i === 2 ? 'r3' : 'other')) }}">{{ $i + 1 }}</div>
                        <div>
                            <div class="product-name">{{ Str::limit($product->product_name, 28) }}</div>
                            <div class="product-meta">{{ $product->order_count }} orders</div>
                        </div>
                        <div class="product-sales">{{ number_format($product->total_qty) }} sold</div>
                    </div>
                    @empty
                    <div style="text-align:center;padding:2rem;color:#a5a3ae;font-size:.84rem;">No sales data yet</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- ── Category Distribution ── --}}
    <div class="row g-3 mb-2">
        <div class="col-xl-6">
            <div class="d-card">
                <div class="d-card-header">
                    <span class="d-card-title"><i class="ri ri-layout-grid-line me-2" style="color:#7367f0"></i>Products by Category</span>
                    <a href="{{ route('admin.categories.index') }}" class="view-all">Manage <i class="ri ri-arrow-right-s-line"></i></a>
                </div>
                <div class="d-card-body">
                    @forelse($catDistribution as $cat)
                    @php
                        $pct = $maxCatProducts > 0 ? round(($cat->products_count / $maxCatProducts) * 100) : 0;
                        $colors = ['#7367f0','#28c76f','#ff9f43','#00cfe8','#ea5455'];
                        $c = $colors[$loop->index % count($colors)];
                    @endphp
                    <div class="cat-bar-row">
                        <div class="cat-bar-label">
                            <span class="cat-bar-name">{{ $cat->name }}</span>
                            <span class="cat-bar-pct">{{ number_format($cat->products_count) }} products</span>
                        </div>
                        <div class="mini-bar">
                            <div class="mini-bar-fill" style="width:{{ $pct }}%;background:{{ $c }};"></div>
                        </div>
                    </div>
                    @empty
                    <div style="text-align:center;padding:1.5rem;color:#a5a3ae;font-size:.84rem;">No categories yet</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Quick Actions ── --}}
        <div class="col-xl-6">
            <div class="d-card h-100">
                <div class="d-card-header">
                    <span class="d-card-title"><i class="ri ri-flashlight-line me-2 text-warning"></i>Quick Actions</span>
                </div>
                <div class="d-card-body">
                    <div class="row g-2">
                        @php
                        $actions = [
                            ['label'=>'Add Product',   'route'=>'admin.products.create',   'icon'=>'ri-add-box-line',        'color'=>'#7367f0', 'bg'=>'rgba(115,103,240,.1)'],
                            ['label'=>'Add Category',  'route'=>'admin.categories.create', 'icon'=>'ri-folder-add-line',     'color'=>'#28c76f', 'bg'=>'rgba(40,199,111,.1)'],
                            ['label'=>'View Orders',   'route'=>'admin.orders.index',      'icon'=>'ri-shopping-bag-3-line', 'color'=>'#00cfe8', 'bg'=>'rgba(0,207,232,.1)'],
                            ['label'=>'All Products',  'route'=>'admin.products.index',    'icon'=>'ri-store-2-line',        'color'=>'#ea5455', 'bg'=>'rgba(234,84,85,.1)'],
                            ['label'=>'All Categories','route'=>'admin.categories.index',  'icon'=>'ri-layout-grid-line',    'color'=>'#9155fd', 'bg'=>'rgba(145,85,253,.1)'],
                        ];
                        @endphp
                        @foreach($actions as $action)
                        <div class="col-4">
                            <a href="{{ route($action['route']) }}"
                               style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.5rem;padding:1rem .5rem;background:{{ $action['bg'] }};border-radius:.625rem;text-decoration:none;transition:transform .2s,box-shadow .2s;"
                               onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,.1)'"
                               onmouseout="this.style.transform='';this.style.boxShadow=''">
                                <i class="ri {{ $action['icon'] }}" style="font-size:1.5rem;color:{{ $action['color'] }};"></i>
                                <span style="font-size:.72rem;font-weight:700;color:{{ $action['color'] }};text-align:center;line-height:1.2;">{{ $action['label'] }}</span>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection