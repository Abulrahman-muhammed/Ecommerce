@extends('admin.layouts.master')

@section('title', 'Trashed Orders')

@push('styles')
<style>
.orders-page { font-family: 'Public Sans', sans-serif; }

.page-header-card {
    background: linear-gradient(135deg, #ea5455 0%, #ff7976 100%);
    border-radius: .5rem; padding: 1.5rem 1.75rem; margin-bottom: 1.5rem;
    position: relative; overflow: hidden;
    box-shadow: 0 4px 18px rgba(234,84,85,.35);
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
    background:rgba(234,84,85,.1); color:#ea5455;
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
.mat-table tbody tr { border-bottom:1px solid rgba(75,70,92,.06); transition:background .15s; }
.mat-table tbody tr:last-child { border-bottom:none; }
.mat-table tbody tr:hover { background:rgba(234,84,85,.02); }
.mat-table td { padding:.9375rem 1.25rem; color:#4b465c; vertical-align:middle; }

.row-num { font-size:.75rem; font-weight:600; color:#b7b5be; }
.order-num { font-weight:700; color:#4b465c; }
.customer-name { font-weight:600; color:#4b465c; }
.customer-email { font-size:.775rem; color:#a8aaae; }
.amount-text { font-weight:700; color:#4b465c; }

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

.date-text { display:flex; align-items:center; gap:.3rem; font-size:.8125rem; color:#a8aaae; }

.act-btn {
    display:inline-flex; align-items:center; justify-content:center;
    width:32px; height:32px; border-radius:.375rem; border:none;
    background:transparent; cursor:pointer; transition:all .18s;
    font-size:.9375rem; text-decoration:none; color:#6d6d6d;
}
.act-btn:hover { background:rgba(75,70,92,.08); }
.act-btn.act-restore:hover { background:rgba(40,199,111,.1); color:#28c76f; }
.act-btn.act-delete:hover  { background:rgba(234,84,85,.1);  color:#ea5455; }

.empty-state { padding:3.5rem 2rem; text-align:center; }
.empty-icon {
    width:64px; height:64px; border-radius:.5rem;
    background:rgba(234,84,85,.08); display:flex; align-items:center;
    justify-content:center; margin:0 auto 1rem; font-size:1.75rem; color:#ea5455;
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
    background:rgba(234,84,85,.08); border-color:#ea5455; color:#ea5455;
}
.pagi-wrap .pagination .page-item.active .page-link {
    background:#ea5455; border-color:#ea5455; color:#fff;
    box-shadow:0 2px 8px rgba(234,84,85,.4);
}

html.swal2-shown, body.swal2-shown { padding-right:0 !important; overflow:auto !important; }
.swal2-container { z-index:9999 !important; }
.swal2-popup {
    font-family:'Public Sans',sans-serif !important;
    border-radius:.5rem !important;
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
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y orders-page">

    {{-- Page Header --}}
    <div class="page-header-card">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="page-title">
                    <i class="ri ri-delete-bin-5-line me-2"></i>Trashed Orders
                </h4>
                <p class="page-breadcrumb mb-2">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    <a href="{{ route('admin.orders.index') }}">Orders</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    Trash
                </p>
                <div class="d-flex gap-2 mt-1">
                    <span class="stat-chip">
                        <i class="ri ri-delete-bin-line"></i>
                        Trashed: <strong>{{ $orders->total() }}</strong>
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

    <div class="table-wrapper">
        <div class="table-header">
            <div class="table-header-title">
                Trashed Orders
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
                        <th>Total</th>
                        <th class="center">Status</th>
                        <th>Deleted</th>
                        <th class="center" style="width:100px">Actions</th>
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
                        <td><span class="order-num">{{ $order->order_number }}</span></td>
                        <td>
                            <div class="customer-name">{{ $order->first_name }} {{ $order->last_name }}</div>
                            <div class="customer-email">{{ $order->email }}</div>
                        </td>
                        <td><span class="amount-text">${{ number_format($order->total_amount, 2) }}</span></td>
                        <td class="center">
                            @php $sc = 's-' . $order->status->color(); @endphp
                            <span class="status-badge {{ $sc }}">
                                <span class="dot"></span>
                                {{ $order->status->label() }}
                            </span>
                        </td>
                        <td>
                            <span class="date-text">
                                <i class="ri ri-time-line"></i>
                                {{ $order->deleted_at->diffForHumans() }}
                            </span>
                        </td>
                        <td class="center">
                            <div class="d-flex justify-content-center gap-1">
                                <form action="{{ route('admin.orders.restore', $order->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="act-btn act-restore" title="Restore">
                                        <i class="ri ri-arrow-go-back-line"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.orders.force-delete', $order->id) }}"
                                      method="POST" class="delete-form d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="act-btn act-delete delete-btn" title="Delete Forever">
                                        <i class="ri ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="ri ri-inbox-line"></i></div>
                                <p class="empty-title">Trash is empty</p>
                                <p class="empty-sub">No trashed orders found</p>
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
            title: 'Delete Permanently?',
            html: `<p style="color:#a8aaae;font-size:.875rem;margin:0">
                       This order will be <strong style="color:#ea5455">permanently deleted</strong>
                       and cannot be recovered.
                   </p>`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: '<i class="ri ri-delete-bin-line me-1"></i> Yes, Delete Forever',
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