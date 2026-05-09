@extends('admin.layouts.master')

@section('title', 'Orders')

@push('styles')
<style>
.orders-page { font-family: 'Public Sans', sans-serif; }

.page-header-card {
    background: linear-gradient(135deg, #696cff 0%, #9155fd 100%);
    border-radius: .5rem;
    padding: 1.5rem 1.75rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(105,108,255,.35);
}
.page-header-card::before {
    content:''; position:absolute; top:-40px; right:-30px;
    width:160px; height:160px; border-radius:50%;
    background:rgba(255,255,255,.07);
}
.page-header-card::after {
    content:''; position:absolute; bottom:-50px; right:60px;
    width:100px; height:100px; border-radius:50%;
    background:rgba(255,255,255,.05);
}
.page-header-card .page-title {
    font-size:1.375rem; font-weight:600; color:#fff; margin:0 0 .25rem;
}
.page-header-card .page-breadcrumb {
    font-size:.8125rem; color:rgba(255,255,255,.75); margin:0;
}
.page-header-card .page-breadcrumb a {
    color:rgba(255,255,255,.85); text-decoration:none;
}
.page-header-card .page-breadcrumb a:hover { color:#fff; }
.stat-chip {
    display:inline-flex; align-items:center; gap:.5rem;
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.2);
    border-radius:50px; padding:.3rem .875rem;
    font-size:.78rem; color:rgba(255,255,255,.9); font-weight:500;
    position:relative; z-index:1;
}
.stat-chip strong { color:#fff; font-weight:700; }
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
.btn-header-ghost:hover {
    background:rgba(255,255,255,.25); color:#fff; transform:translateY(-1px);
}

.filter-wrapper {
    background:#fff; border-radius:.5rem;
    border:1px solid rgba(75,70,92,.08);
    box-shadow:0 2px 6px rgba(75,70,92,.06);
    padding:1.25rem 1.5rem; margin-bottom:1.5rem;
}
.filter-title {
    font-size:.6875rem; font-weight:700; letter-spacing:.08em;
    text-transform:uppercase; color:#a8aaae; margin-bottom:1rem;
    display:flex; align-items:center; gap:.375rem;
}
.filter-title::after { content:''; flex:1; height:1px; background:rgba(75,70,92,.08); }
.form-label-sm {
    font-size:.75rem; font-weight:600; color:#4b465c;
    margin-bottom:.375rem; display:block;
}
.mat-input {
    width:100%; background:#fafafa; border:1px solid rgba(75,70,92,.12);
    border-radius:.375rem; color:#4b465c; font-family:'Public Sans',sans-serif;
    font-size:.875rem; padding:.4375rem .875rem; outline:none;
    transition:border-color .2s,box-shadow .2s;
}
.mat-input::placeholder { color:#a8aaae; }
.mat-input:focus { border-color:#696cff; box-shadow:0 0 0 3px rgba(105,108,255,.12); }
.input-icon-wrap { position:relative; }
.input-icon-wrap .input-icon {
    position:absolute; left:.75rem; top:50%; transform:translateY(-50%);
    color:#a8aaae; font-size:.9375rem; pointer-events:none;
}
.input-icon-wrap .mat-input { padding-left:2.125rem; }
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
.btn-mat-primary {
    display:inline-flex; align-items:center; justify-content:center; gap:.375rem;
    font-family:'Public Sans',sans-serif; font-size:.8125rem; font-weight:600;
    color:#fff; background:#696cff; border:none; border-radius:.375rem;
    padding:.4375rem 1.125rem; cursor:pointer; transition:all .2s;
    box-shadow:0 2px 8px rgba(105,108,255,.35); flex:1;
}
.btn-mat-primary:hover {
    background:#5f61e6; box-shadow:0 4px 14px rgba(105,108,255,.45); transform:translateY(-1px);
}
.btn-mat-outline {
    display:inline-flex; align-items:center; justify-content:center;
    font-family:'Public Sans',sans-serif; font-size:.875rem; color:#6d6d6d;
    background:#fff; border:1px solid rgba(75,70,92,.2); border-radius:.375rem;
    padding:.4375rem .8125rem; text-decoration:none; transition:all .2s;
}
.btn-mat-outline:hover { border-color:#696cff; color:#696cff; background:rgba(105,108,255,.06); }

.table-wrapper {
    background:#fff; border-radius:.5rem;
    border:1px solid rgba(75,70,92,.08);
    box-shadow:0 2px 6px rgba(75,70,92,.06); overflow:hidden;
}
.table-header {
    display:flex; align-items:center; justify-content:space-between;
    padding:1rem 1.5rem; border-bottom:1px solid rgba(75,70,92,.07);
}
.table-header-title { font-size:.9375rem; font-weight:600; color:#4b465c; }
.table-count-badge {
    display:inline-flex; align-items:center;
    background:rgba(105,108,255,.1); color:#696cff;
    border-radius:50px; font-size:.6875rem; font-weight:700;
    padding:2px 10px; margin-left:.5rem;
}
.mat-table { width:100%; border-collapse:collapse; font-size:.875rem; }
.mat-table thead tr { background:#fafafa; border-bottom:1px solid rgba(75,70,92,.08); }
.mat-table th {
    font-family:'Public Sans',sans-serif; font-size:.6875rem; font-weight:700;
    letter-spacing:.08em; text-transform:uppercase; color:#a8aaae;
    padding:.875rem 1.25rem; white-space:nowrap;
}
.mat-table th.center, .mat-table td.center { text-align:center; }
.mat-table tbody tr { border-bottom:1px solid rgba(75,70,92,.06); transition:background .15s; }
.mat-table tbody tr:last-child { border-bottom:none; }
.mat-table tbody tr:hover { background:rgba(105,108,255,.03); }
.mat-table td { padding:.9375rem 1.25rem; color:#4b465c; vertical-align:middle; }

.row-num { font-size:.75rem; font-weight:600; color:#b7b5be; }
.order-num-link { font-weight:700; color:#4b465c; text-decoration:none; font-size:.875rem; }
.order-num-link:hover { color:#696cff; }
.customer-name { font-weight:600; color:#4b465c; font-size:.875rem; }
.customer-email { font-size:.775rem; color:#a8aaae; margin-top:1px; }
.amount-text { font-weight:700; color:#4b465c; }

.status-badge {
    display:inline-flex; align-items:center; gap:.3rem;
    font-size:.6875rem; font-weight:700; letter-spacing:.05em;
    text-transform:uppercase; border-radius:.25rem; padding:3px 10px;
}
.status-badge .dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }
.s-warning  { background:rgba(255,159,67,.12);  color:#ff9f43; }
.s-warning .dot  { background:#ff9f43; }
.s-success  { background:rgba(40,199,111,.12);  color:#28c76f; }
.s-success .dot  { background:#28c76f; }
.s-danger   { background:rgba(234,84,85,.12);   color:#ea5455; }
.s-danger .dot   { background:#ea5455; }
.s-info     { background:rgba(0,207,232,.12);   color:#00cfe8; }
.s-info .dot     { background:#00cfe8; }

.date-text { display:flex; align-items:center; gap:.3rem; font-size:.8125rem; color:#a8aaae; }

.act-btn {
    display:inline-flex; align-items:center; justify-content:center;
    width:32px; height:32px; border-radius:.375rem; border:none;
    background:transparent; cursor:pointer; transition:all .18s;
    font-size:.9375rem; text-decoration:none; color:#6d6d6d;
}
.act-btn:hover { background:rgba(75,70,92,.08); }
.act-btn.act-view:hover   { background:rgba(105,108,255,.1); color:#696cff; }
.act-btn.act-delete:hover { background:rgba(234,84,85,.1);   color:#ea5455; }

.empty-state { padding:3.5rem 2rem; text-align:center; }
.empty-icon {
    width:64px; height:64px; border-radius:.5rem;
    background:rgba(105,108,255,.08); display:flex; align-items:center;
    justify-content:center; margin:0 auto 1rem; font-size:1.75rem; color:#696cff;
}
.empty-title { font-size:1rem; font-weight:600; color:#4b465c; margin:0 0 .375rem; }
.empty-sub   { font-size:.875rem; color:#a8aaae; margin:0; }

.pagi-wrap {
    padding:.875rem 1.5rem; border-top:1px solid rgba(75,70,92,.07);
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:.5rem;
}
.pagi-info { font-size:.8125rem; color:#a8aaae; }
.pagi-wrap .pagination .page-link {
    background:#fff; border-color:rgba(75,70,92,.12); color:#4b465c;
    font-size:.8125rem; border-radius:.375rem !important; margin:0 2px;
    transition:all .15s; padding:.3125rem .625rem; min-width:32px; text-align:center;
}
.pagi-wrap .pagination .page-link:hover {
    background:rgba(105,108,255,.08); border-color:#696cff; color:#696cff;
}
.pagi-wrap .pagination .page-item.active .page-link {
    background:#696cff; border-color:#696cff; color:#fff;
    box-shadow:0 2px 8px rgba(105,108,255,.4);
}

html.swal2-shown, body.swal2-shown { padding-right:0 !important; overflow:auto !important; }
.swal2-container { z-index:9999 !important; }
.swal2-popup {
    font-family:'Public Sans',sans-serif !important;
    border-radius:.5rem !important;
    box-shadow:0 8px 40px rgba(75,70,92,.18) !important;
}
.swal2-title { font-size:1.125rem !important; font-weight:600 !important; color:#4b465c !important; }
.swal2-html-container { font-size:.875rem !important; color:#a8aaae !important; }

@keyframes rowFade {
    from { opacity:0; transform:translateY(6px); }
    to   { opacity:1; transform:translateY(0); }
}
.mat-table tbody tr { animation:rowFade .28s ease both; }
.mat-table tbody tr:nth-child(1){animation-delay:.02s}
.mat-table tbody tr:nth-child(2){animation-delay:.06s}
.mat-table tbody tr:nth-child(3){animation-delay:.10s}
.mat-table tbody tr:nth-child(4){animation-delay:.14s}
.mat-table tbody tr:nth-child(5){animation-delay:.18s}
.mat-table tbody tr:nth-child(6){animation-delay:.22s}
.mat-table tbody tr:nth-child(7){animation-delay:.26s}
.mat-table tbody tr:nth-child(8){animation-delay:.30s}
.mat-table tbody tr:nth-child(9){animation-delay:.34s}
.mat-table tbody tr:nth-child(10){animation-delay:.38s}
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y orders-page">

    {{-- Page Header --}}
    <div class="page-header-card">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="page-title">
                    <i class="ri ri-shopping-bag-3-line me-2"></i>Orders
                </h4>
                <p class="page-breadcrumb mb-2">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    Manage Orders
                </p>
                <div class="d-flex gap-2 flex-wrap mt-1">
                    <span class="stat-chip">
                        <i class="ri ri-file-list-3-line"></i>
                        Total: <strong>{{ $orders->total() }}</strong>
                    </span>
                    <span class="stat-chip">
                        <i class="ri ri-eye-line"></i>
                        Showing: <strong>{{ $orders->count() }}</strong>
                    </span>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.orders.trashed') }}" class="btn-header-ghost">
                    <i class="ri ri-delete-bin-line"></i> Trashed
                </a>
            </div>
        </div>
    </div>

    <x-alert/>

    {{-- Filters --}}
    <div class="filter-wrapper">
        <div class="filter-title">
            <i class="ri ri-equalizer-line" style="color:#a8aaae"></i>
            Filters
        </div>
        <form action="{{ route('admin.orders.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label-sm">Search</label>
                    <div class="input-icon-wrap">
                        <i class="ri ri-search-line input-icon"></i>
                        <input type="text" name="search" class="mat-input"
                               placeholder="Order # or email…"
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label-sm">Order Status</label>
                    <div class="select-wrap">
                        <select name="status" class="mat-select">
                            <option value="">All Statuses</option>
                            @foreach($orderStatuses as $s)
                                <option value="{{ $s->value }}" @selected(request('status') == $s->value)>
                                    {{ $s->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label-sm">Payment Status</label>
                    <div class="select-wrap">
                        <select name="payment_status" class="mat-select">
                            <option value="">All Payments</option>
                            @foreach($paymentStatuses as $s)
                                <option value="{{ $s->value }}" @selected(request('payment_status') == $s->value)>
                                    {{ $s->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn-mat-primary">
                        <i class="ri ri-filter-3-line"></i> Filter
                    </button>
                    @if(request()->hasAny(['search','status','payment_status']))
                        <a href="{{ route('admin.orders.index') }}" class="btn-mat-outline" title="Reset">
                            <i class="ri ri-refresh-line"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-wrapper">
        <div class="table-header">
            <div class="table-header-title">
                All Orders
                <span class="table-count-badge">{{ $orders->total() }}</span>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table class="mat-table">
                <thead>
                    <tr>
                        <th style="width:52px">#</th>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th class="center">Status</th>
                        <th class="center">Payment</th>
                        <th>Date</th>
                        <th class="center" style="width:90px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <span class="row-num">
                                {{ str_pad(($orders->currentPage()-1)*$orders->perPage()+$loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="order-num-link">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td>
                            <div class="customer-name">{{ $order->first_name }} {{ $order->last_name }}</div>
                            <div class="customer-email">{{ $order->email }}</div>
                        </td>
                        <td>{{ $order->items->count() }} item(s)</td>
                        <td><span class="amount-text">${{ number_format($order->total_amount, 2) }}</span></td>

                        {{-- Order Status --}}
                        <td class="center">
                            @php $sc = 's-' . $order->status->color(); @endphp
                            <span class="status-badge {{ $sc }}">
                                <span class="dot"></span>
                                {{ $order->status->label() }}
                            </span>
                        </td>

                        {{-- Payment Status --}}
                        <td class="center">
                            @php $pc = 's-' . $order->payment_status->color(); @endphp
                            <span class="status-badge {{ $pc }}">
                                <span class="dot"></span>
                                {{ $order->payment_status->label() }}
                            </span>
                        </td>

                        <td>
                            <span class="date-text">
                                <i class="ri ri-time-line"></i>
                                {{ $order->created_at->diffForHumans() }}
                            </span>
                        </td>
                        <td class="center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="act-btn act-view" title="View">
                                    <i class="ri ri-eye-line"></i>
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order) }}"
                                      method="POST" class="delete-form d-inline m-0 p-0">
                                    @csrf @method('DELETE')
                                    <button type="button" class="act-btn act-delete delete-btn" title="Move to Trash">
                                        <i class="ri ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="ri ri-inbox-unarchive-line"></i></div>
                                <p class="empty-title">No orders found</p>
                                <p class="empty-sub">Try adjusting your filters</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="pagi-wrap">
            <span class="pagi-info">
                Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }} entries
            </span>
            {{ $orders->links('pagination::bootstrap-5') }}
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
                       This order will be moved to the
                       <strong style="color:#696cff">Trash</strong>.
                       You can restore it anytime.
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