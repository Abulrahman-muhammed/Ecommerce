@extends('admin.layouts.master')

@section('title', 'Trashed Customers')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap');

    :root {
        --trash-bg:           #f4f5fb;
        --trash-surface:      #ffffff;
        --trash-surface-2:    #f0f1f8;
        --trash-border:       rgba(75, 70, 120, 0.10);
        --trash-border-hover: rgba(75, 70, 120, 0.22);
        --trash-danger:       #e0364a;
        --trash-danger-soft:  rgba(224, 54, 74, 0.09);
        --trash-success:      #1a9e74;
        --trash-success-soft: rgba(26, 158, 116, 0.09);
        --trash-accent:       #7367f0;
        --trash-accent-soft:  rgba(115, 103, 240, 0.10);
        --trash-text:         #3d3a5c;
        --trash-muted:        #9e9bb8;
    }

    .trash-page { font-family:'DM Sans',sans-serif;background:var(--trash-bg);min-height:100vh;padding:2rem 1.5rem 3rem;color:var(--trash-text); }

    /* Header */
    .trash-header { display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1.25rem;margin-bottom:2rem; }
    .trash-eyebrow {
        display:inline-flex;align-items:center;gap:6px;
        font-family:'Syne',sans-serif;font-size:.68rem;font-weight:700;
        letter-spacing:.12em;text-transform:uppercase;
        color:var(--trash-danger);background:var(--trash-danger-soft);
        border:1px solid rgba(255,77,109,.2);border-radius:50px;
        padding:4px 12px;margin-bottom:.65rem;
    }
    .trash-title { font-family:'Syne',sans-serif;font-size:1.85rem;font-weight:600;color:var(--trash-text);margin:0 0 .25rem;line-height:1.2; }
    .trash-subtitle { font-size:.875rem;color:var(--trash-muted);margin:0; }
    .btn-back {
        display:inline-flex;align-items:center;gap:8px;
        font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:500;
        color:var(--trash-text);background:var(--trash-surface-2);
        border:1px solid var(--trash-border);border-radius:10px;
        padding:10px 18px;text-decoration:none;transition:all .2s;white-space:nowrap;align-self:flex-start;
    }
    .btn-back:hover { background:var(--trash-accent-soft);border-color:var(--trash-accent);color:var(--trash-accent);transform:translateX(-2px); }

    /* Filter */
    .filter-card { background:var(--trash-surface);border:1px solid var(--trash-border);border-radius:14px;padding:1.25rem 1.5rem;margin-bottom:1.5rem; }
    .filter-label { font-family:'Syne',sans-serif;font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--trash-muted);display:block;margin-bottom:.4rem; }
    .filter-input-wrap { position:relative;display:flex;align-items:center; }
    .filter-input-wrap i { position:absolute;left:12px;color:var(--trash-muted);font-size:1rem;pointer-events:none; }
    .filter-input {
        width:100%;background:var(--trash-surface-2);border:1px solid var(--trash-border);
        border-radius:10px;color:var(--trash-text);font-family:'DM Sans',sans-serif;
        font-size:.875rem;padding:9px 14px 9px 38px;outline:none;transition:border-color .2s;
    }
    .filter-input::placeholder { color:var(--trash-muted); }
    .filter-input:focus { border-color:var(--trash-accent); }
    .filter-select {
        width:100%;background:var(--trash-surface-2);border:1px solid var(--trash-border);
        border-radius:10px;color:var(--trash-text);font-family:'DM Sans',sans-serif;
        font-size:.875rem;padding:9px 14px;outline:none;appearance:none;cursor:pointer;transition:border-color .2s;
    }
    .filter-select:focus { border-color:var(--trash-accent); }
    .btn-filter {
        display:inline-flex;align-items:center;justify-content:center;gap:6px;
        font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:500;
        background:var(--trash-accent);color:#fff;border:none;border-radius:10px;
        padding:9px 18px;cursor:pointer;transition:all .2s;flex:1;
    }
    .btn-filter:hover { opacity:.88; }
    .btn-reset {
        display:inline-flex;align-items:center;justify-content:center;
        background:var(--trash-surface-2);border:1px solid var(--trash-border);border-radius:10px;
        color:var(--trash-muted);padding:9px 13px;text-decoration:none;transition:all .2s;
    }
    .btn-reset:hover { border-color:var(--trash-border-hover);color:var(--trash-text); }

    /* Table */
    .table-card { background:var(--trash-surface);border:1px solid var(--trash-border);border-radius:16px;overflow:hidden; }
    .trash-table { width:100%;border-collapse:collapse;font-size:.875rem; }
    .trash-table thead tr { border-bottom:1px solid var(--trash-border); }
    .trash-table th {
        font-family:'Syne',sans-serif;font-size:.65rem;font-weight:700;
        letter-spacing:.1em;text-transform:uppercase;color:var(--trash-muted);
        padding:14px 20px;white-space:nowrap;
    }
    .trash-table th.center, .trash-table td.center { text-align:center; }
    .trash-table tbody tr { border-bottom:1px solid var(--trash-border);transition:background .18s; }
    .trash-table tbody tr:last-child { border-bottom:none; }
    .trash-table tbody tr:hover { background:var(--trash-surface-2); }
    .trash-table td { padding:14px 20px;color:var(--trash-text);vertical-align:middle; }
    .row-num { font-family:'Syne',sans-serif;font-size:.75rem;font-weight:700;color:var(--trash-muted); }

    /* Customer cell */
    .user-avatar {
        width:40px;height:40px;border-radius:50%;object-fit:cover;
        border:2px solid rgba(115,103,240,.2);flex-shrink:0;
    }
    .user-avatar-placeholder {
        width:40px;height:40px;border-radius:50%;
        background:linear-gradient(135deg,#696cff,#9155fd);
        display:flex;align-items:center;justify-content:center;
        color:#fff;font-size:.875rem;font-weight:700;flex-shrink:0;
    }
    .user-name { font-weight:600;color:var(--trash-text);font-size:.9rem;line-height:1.3; }
    .user-email { font-size:.75rem;color:var(--trash-muted);margin-top:2px; }

    /* Badges */
    .badge-role {
        display:inline-flex;align-items:center;
        font-family:'Syne',sans-serif;font-size:.62rem;font-weight:700;
        letter-spacing:.08em;text-transform:uppercase;border-radius:50px;padding:4px 12px;
    }
    .role-admin   { background:rgba(105,108,255,.1);color:#696cff;border:1px solid rgba(105,108,255,.2); }
    .role-store   { background:rgba(255,159,67,.1); color:#ff9f43;border:1px solid rgba(255,159,67,.2); }
    .role-user    { background:rgba(168,170,174,.1);color:#a8aaae;border:1px solid rgba(168,170,174,.2); }

    .badge-verified {
        display:inline-flex;align-items:center;gap:4px;
        font-family:'Syne',sans-serif;font-size:.62rem;font-weight:700;
        letter-spacing:.08em;text-transform:uppercase;border-radius:50px;padding:4px 12px;
    }
    .verified { background:rgba(26,158,116,.09);color:#1a9e74;border:1px solid rgba(59,232,176,.2); }
    .unverified { background:var(--trash-danger-soft);color:var(--trash-danger);border:1px solid rgba(255,77,109,.2); }

    .deleted-date { font-size:.83rem;color:var(--trash-text); }
    .deleted-ago  { font-size:.75rem;color:var(--trash-danger);margin-top:2px; }

    /* Action buttons */
    .action-btn {
        display:inline-flex;align-items:center;justify-content:center;
        width:34px;height:34px;border-radius:9px;border:1px solid var(--trash-border);
        background:transparent;cursor:pointer;transition:all .18s;font-size:1.05rem;text-decoration:none;
    }
    .action-btn.restore { color:var(--trash-success); }
    .action-btn.restore:hover { background:var(--trash-success-soft);border-color:rgba(59,232,176,.3);transform:scale(1.08); }
    .action-btn.delete  { color:var(--trash-danger); }
    .action-btn.delete:hover  { background:var(--trash-danger-soft); border-color:rgba(255,77,109,.3);transform:scale(1.08); }

    /* Empty state */
    .empty-state { padding:4rem 2rem;text-align:center; }
    .empty-icon-wrap {
        width:72px;height:72px;border-radius:20px;
        background:var(--trash-surface-2);border:1px solid var(--trash-border);
        display:flex;align-items:center;justify-content:center;
        margin:0 auto 1.25rem;font-size:2rem;color:var(--trash-muted);
    }
    .empty-title { font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:700;color:var(--trash-text);margin:0 0 .4rem; }
    .empty-subtitle { font-size:.875rem;color:var(--trash-muted);margin:0; }

    /* Pagination */
    .pagination-wrap { padding:1rem 1.5rem;border-top:1px solid var(--trash-border);display:flex;justify-content:flex-end; }
    .pagination-wrap .pagination .page-link {
        background:var(--trash-surface-2);border-color:var(--trash-border);color:var(--trash-muted);
        font-size:.82rem;border-radius:8px !important;margin:0 2px;transition:all .18s;
    }
    .pagination-wrap .pagination .page-link:hover { background:var(--trash-accent-soft);border-color:var(--trash-accent);color:var(--trash-accent); }
    .pagination-wrap .pagination .page-item.active .page-link { background:var(--trash-accent);border-color:var(--trash-accent);color:#fff; }

    html.swal2-shown, body.swal2-shown { padding-right:0 !important;overflow:auto !important; }
    .swal2-container { z-index:9999 !important; }
    .swal2-popup { background:#ffffff !important;border:1px solid var(--trash-border) !important;border-radius:16px !important;font-family:'DM Sans',sans-serif !important; }
    .swal2-title { color:var(--trash-text) !important;font-family:'Syne',sans-serif !important; }
    .swal2-html-container { color:var(--trash-muted) !important; }

    @keyframes rowIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
    .trash-table tbody tr { animation:rowIn .3s ease both; }
    .trash-table tbody tr:nth-child(1)  { animation-delay:.00s; }
    .trash-table tbody tr:nth-child(2)  { animation-delay:.04s; }
    .trash-table tbody tr:nth-child(3)  { animation-delay:.08s; }
    .trash-table tbody tr:nth-child(4)  { animation-delay:.12s; }
    .trash-table tbody tr:nth-child(5)  { animation-delay:.16s; }
</style>
@endpush

@section('content')
<div class="trash-page">

    {{-- Header --}}
    <div class="trash-header">
        <div>
            <div class="trash-eyebrow"><i class="ri ri-delete-bin-3-line"></i> Recycle Bin</div>
            <h1 class="trash-title">Trashed Customers</h1>
            <p class="trash-subtitle">Restore or permanently remove deleted customers</p>
        </div>
        <a href="{{ route('admin.customers.index') }}" class="btn-back">
            <i class="ri ri-arrow-left-line"></i> Back to Customers
        </a>
    </div>

    {{-- Filter --}}
    <div class="filter-card">
        <form action="{{ route('admin.customers.trashed') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="filter-label">Search</label>
                    <div class="filter-input-wrap">
                        <i class="ri ri-search-line"></i>
                        <input type="text" name="search" class="filter-input" placeholder="Search by name or email…" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="filter-label">Verified</label>
                    <select name="verified" class="filter-select">
                        <option value="">All</option>
                        <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Verified</option>
                        <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>Unverified</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="filter-label">Provider</label>
                    <select name="provider" class="filter-select">
                        <option value="">All</option>
                        <option value="local" {{ request('provider') === 'local' ? 'selected' : '' }}>Local</option>
                        <option value="google" {{ request('provider') === 'google' ? 'selected' : '' }}>Google</option>
                        <option value="facebook" {{ request('provider') === 'facebook' ? 'selected' : '' }}>Facebook</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn-filter">
                        <i class="ri ri-filter-3-line"></i> Apply Filter
                    </button>
                    <a href="{{ route('admin.customers.trashed') }}" class="btn-reset" title="Clear filters">
                        <i class="ri ri-refresh-line"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-card">
        <div style="overflow-x:auto;">
            <table class="trash-table">
                <thead>
                    <tr>
                        <th style="width:48px">#</th>
                        <th>Customer</th>
                        <th class="center">Verified</th>
                        <th>Provider</th>
                        <th>Deleted</th>
                        <th class="center" style="width:100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                    <tr>
                        <td><span class="row-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span></td>

                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($customer->avatar)
                                    <img src="{{ asset('storage/'.$customer->avatar) }}" alt="{{ $customer->name }}" class="user-avatar">
                                @else
                                    <div class="user-avatar-placeholder">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="user-name">{{ $customer->name }}</div>
                                    <div class="user-email">{{ $customer->email }}</div>
                                </div>
                            </div>
                        </td>



                        <td class="center">
                            @if($customer->email_verified_at)
                                <span class="badge-verified verified">
                                    <i class="ri ri-shield-check-line"></i> Yes
                                </span>
                            @else
                                <span class="badge-verified unverified">
                                    <i class="ri ri-shield-cross-line"></i> No
                                </span>
                            @endif
                        </td>

                        <td style="font-size:.83rem;color:#6d6d6d;">
                            <i class="ri ri-{{ ($customer->provider && $customer->provider !== 'local') ? $customer->provider.'-line' : 'lock-password-line' }}"></i>
                            {{ ucfirst($customer->provider ?? 'local') }}
                        </td>

                        <td>
                            <div class="deleted-date">{{ $customer->deleted_at->format('Y-m-d') }}</div>
                            <div class="deleted-ago">{{ $customer->deleted_at->diffForHumans() }}</div>
                        </td>

                        <td class="center">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- Restore --}}
                                <form action="{{ route('admin.customers.restore', $customer->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="action-btn restore" title="Restore customer">
                                        <i class="ri ri-restart-line"></i>
                                    </button>
                                </form>

                                {{-- Force Delete --}}
                                <form action="{{ route('admin.customers.force-delete', $customer->id) }}" method="POST" class="delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="action-btn delete delete-btn" title="Delete permanently">
                                        <i class="ri ri-delete-bin-2-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-icon-wrap"><i class="ri ri-user-unfollow-line"></i></div>
                                <p class="empty-title">Trash is empty</p>
                                <p class="empty-subtitle">No trashed customers found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
        <div class="pagination-wrap">
            {{ $customers->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function () {
        const form = this.closest('.delete-form');
        Swal.fire({
            title: 'Delete permanently?',
            html: '<span style="color:#9e9bb8;font-size:.9rem">This action <strong style="color:#e0364a">cannot be undone</strong>. The customer will be removed forever.</span>',
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            scrollbarPadding: false,
            customClass: {
                confirmButton: 'btn btn-danger me-3',
                cancelButton:  'btn btn-secondary'
            },
            buttonsStyling: false
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush