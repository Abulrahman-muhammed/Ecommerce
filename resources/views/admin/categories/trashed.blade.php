@extends('admin.layouts.master')

@section('title', 'Trashed Categories')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap');

    :root {
        --trash-bg: #f4f5fb;
        --trash-surface: #ffffff;
        --trash-surface-2: #f0f1f8;
        --trash-border: rgba(75, 70, 120, 0.10);
        --trash-border-hover: rgba(75, 70, 120, 0.22);
        --trash-danger: #e0364a;
        --trash-danger-soft: rgba(224, 54, 74, 0.09);
        --trash-success: #1a9e74;
        --trash-success-soft: rgba(26, 158, 116, 0.09);
        --trash-accent: #7367f0;
        --trash-accent-soft: rgba(115, 103, 240, 0.10);
        --trash-text: #3d3a5c;
        --trash-muted: #9e9bb8;
        --trash-tag: #ececf5;
    }

    /* ─── Page wrapper ─── */
    .trash-page {
        font-family: 'DM Sans', sans-serif;
        background: var(--trash-bg);
        min-height: 100vh;
        padding: 2rem 1.5rem 3rem;
        color: var(--trash-text);
    }

    /* ─── Header ─── */
    .trash-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .trash-header-left {}

    .trash-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'Syne', sans-serif;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--trash-danger);
        background: var(--trash-danger-soft);
        border: 1px solid rgba(255, 77, 109, 0.2);
        border-radius: 50px;
        padding: 4px 12px;
        margin-bottom: 0.65rem;
    }

    .trash-title {
        font-family: 'Syne', sans-serif;
        font-size: 1.85rem;
        font-weight: 600;
        color: var(--trash-text);
        margin: 0 0 0.25rem;
        line-height: 1.2;
    }

    .trash-subtitle {
        font-size: 0.875rem;
        color: var(--trash-muted);
        margin: 0;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--trash-text);
        background: var(--trash-surface-2);
        border: 1px solid var(--trash-border);
        border-radius: 10px;
        padding: 10px 18px;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
        align-self: flex-start;
    }
    .btn-back:hover {
        background: var(--trash-accent-soft);
        border-color: var(--trash-accent);
        color: var(--trash-accent);
        transform: translateX(-2px);
    }
    .btn-back i { font-size: 1rem; }

    /* ─── Filter card ─── */
    .filter-card {
        background: var(--trash-surface);
        border: 1px solid var(--trash-border);
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .filter-label {
        font-family: 'Syne', sans-serif;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--trash-muted);
        display: block;
        margin-bottom: 0.4rem;
    }

    .filter-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }
    .filter-input-wrap i {
        position: absolute;
        left: 12px;
        color: var(--trash-muted);
        font-size: 1rem;
        pointer-events: none;
    }
    .filter-input {
        width: 100%;
        background: var(--trash-surface-2);
        border: 1px solid var(--trash-border);
        border-radius: 10px;
        color: var(--trash-text);
        font-family: 'DM Sans', sans-serif;
        font-size: 0.875rem;
        padding: 9px 14px 9px 38px;
        outline: none;
        transition: border-color 0.2s;
    }
    .filter-input::placeholder { color: var(--trash-muted); }
    .filter-input:focus { border-color: var(--trash-accent); }

    .filter-select {
        width: 100%;
        background: var(--trash-surface-2);
        border: 1px solid var(--trash-border);
        border-radius: 10px;
        color: var(--trash-text);
        font-family: 'DM Sans', sans-serif;
        font-size: 0.875rem;
        padding: 9px 14px;
        outline: none;
        appearance: none;
        cursor: pointer;
        transition: border-color 0.2s;
    }
    .filter-select:focus { border-color: var(--trash-accent); }
    .filter-select option { background: var(--trash-surface-2); }

    .btn-filter {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.85rem;
        font-weight: 500;
        background: var(--trash-accent);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 9px 18px;
        cursor: pointer;
        transition: all 0.2s;
        flex: 1;
    }
    .btn-filter:hover { opacity: 0.88; }

    .btn-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--trash-surface-2);
        border: 1px solid var(--trash-border);
        border-radius: 10px;
        color: var(--trash-muted);
        padding: 9px 13px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-reset:hover { border-color: var(--trash-border-hover); color: var(--trash-text); }

    /* ─── Table card ─── */
    .table-card {
        background: var(--trash-surface);
        border: 1px solid var(--trash-border);
        border-radius: 16px;
        overflow: hidden;
    }

    .trash-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .trash-table thead tr {
        border-bottom: 1px solid var(--trash-border);
    }

    .trash-table th {
        font-family: 'Syne', sans-serif;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--trash-muted);
        padding: 14px 20px;
        background: transparent;
        white-space: nowrap;
    }

    .trash-table th.center,
    .trash-table td.center { text-align: center; }

    .trash-table tbody tr {
        border-bottom: 1px solid var(--trash-border);
        transition: background 0.18s;
    }
    .trash-table tbody tr:last-child { border-bottom: none; }
    .trash-table tbody tr:hover { background: var(--trash-surface-2); }

    .trash-table td {
        padding: 14px 20px;
        color: var(--trash-text);
        vertical-align: middle;
    }

    /* ─── Row number ─── */
    .row-num {
        font-family: 'Syne', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--trash-muted);
    }

    /* ─── Category info ─── */
    .cat-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid var(--trash-border);
        flex-shrink: 0;
    }
    .cat-avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--trash-tag);
        border: 1px solid var(--trash-border);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--trash-muted);
        font-size: 1.1rem;
    }
    .cat-name {
        font-weight: 600;
        color: var(--trash-text);
        font-size: 0.9rem;
        line-height: 1.3;
    }
    .cat-slug {
        font-size: 0.75rem;
        color: var(--trash-muted);
        font-family: 'DM Mono', monospace;
        margin-top: 2px;
    }

    /* ─── Badges ─── */
    .badge-parent {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.75rem;
        font-weight: 500;
        background: var(--trash-accent-soft);
        color: var(--trash-accent);
        border: 1px solid rgba(123,110,246,0.2);
        border-radius: 6px;
        padding: 3px 10px;
    }
    .badge-parent i { font-size: 0.7rem; }

    .badge-status {
        display: inline-block;
        font-family: 'Syne', sans-serif;
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        border-radius: 50px;
        padding: 4px 12px;
    }
    .badge-status.active {
        background: var(--trash-success-soft);
        color: var(--trash-success);
        border: 1px solid rgba(59,232,176,0.2);
    }
    .badge-status.inactive, .badge-status.default {
        background: var(--trash-tag);
        color: var(--trash-muted);
        border: 1px solid var(--trash-border);
    }
    .badge-status.danger {
        background: var(--trash-danger-soft);
        color: var(--trash-danger);
        border: 1px solid rgba(255,77,109,0.2);
    }

    /* ─── Deleted at ─── */
    .deleted-date { font-size: 0.83rem; color: var(--trash-text); }
    .deleted-ago  { font-size: 0.75rem; color: var(--trash-danger); margin-top: 2px; }

    /* ─── Action buttons ─── */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 9px;
        border: 1px solid var(--trash-border);
        background: transparent;
        cursor: pointer;
        transition: all 0.18s;
        font-size: 1.05rem;
        text-decoration: none;
    }
    .action-btn.restore {
        color: var(--trash-success);
    }
    .action-btn.restore:hover {
        background: var(--trash-success-soft);
        border-color: rgba(59,232,176,0.3);
        transform: scale(1.08);
    }
    .action-btn.delete {
        color: var(--trash-danger);
    }
    .action-btn.delete:hover {
        background: var(--trash-danger-soft);
        border-color: rgba(255,77,109,0.3);
        transform: scale(1.08);
    }

    /* ─── Empty state ─── */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }
    .empty-icon-wrap {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        background: var(--trash-surface-2);
        border: 1px solid var(--trash-border);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        font-size: 2rem;
        color: var(--trash-muted);
    }
    .empty-title {
        font-family: 'Syne', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--trash-text);
        margin: 0 0 0.4rem;
    }
    .empty-subtitle { font-size: 0.875rem; color: var(--trash-muted); margin: 0; }

    /* ─── Pagination wrapper ─── */
    .pagination-wrap {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--trash-border);
        display: flex;
        justify-content: flex-end;
    }
    .pagination-wrap .pagination .page-link {
        background: var(--trash-surface-2);
        border-color: var(--trash-border);
        color: var(--trash-muted);
        font-size: 0.82rem;
        border-radius: 8px !important;
        margin: 0 2px;
        transition: all 0.18s;
    }
    .pagination-wrap .pagination .page-link:hover {
        background: var(--trash-accent-soft);
        border-color: var(--trash-accent);
        color: var(--trash-accent);
    }
    .pagination-wrap .pagination .page-item.active .page-link {
        background: var(--trash-accent);
        border-color: var(--trash-accent);
        color: #fff;
    }

    /* ─── SweetAlert fix ─── */
    html.swal2-shown, body.swal2-shown { padding-right: 0 !important; overflow: auto !important; }
    .swal2-container { z-index: 9999 !important; }
    .swal2-popup {
        background: #ffffff !important;
        color: var(--trash-text) !important;
        border: 1px solid var(--trash-border) !important;
        border-radius: 16px !important;
        font-family: 'DM Sans', sans-serif !important;
        box-shadow: 0 20px 60px rgba(115,103,240,0.12) !important;
    }
    .swal2-title { color: var(--trash-text) !important; font-family: 'Syne', sans-serif !important; }
    .swal2-html-container { color: var(--trash-muted) !important; }

    /* ─── Row entry animation ─── */
    @keyframes rowIn {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .trash-table tbody tr { animation: rowIn 0.3s ease both; }
    .trash-table tbody tr:nth-child(1)  { animation-delay: 0.00s; }
    .trash-table tbody tr:nth-child(2)  { animation-delay: 0.04s; }
    .trash-table tbody tr:nth-child(3)  { animation-delay: 0.08s; }
    .trash-table tbody tr:nth-child(4)  { animation-delay: 0.12s; }
    .trash-table tbody tr:nth-child(5)  { animation-delay: 0.16s; }
    .trash-table tbody tr:nth-child(6)  { animation-delay: 0.20s; }
    .trash-table tbody tr:nth-child(7)  { animation-delay: 0.24s; }
    .trash-table tbody tr:nth-child(8)  { animation-delay: 0.28s; }
    .trash-table tbody tr:nth-child(9)  { animation-delay: 0.32s; }
    .trash-table tbody tr:nth-child(10) { animation-delay: 0.36s; }
    .trash-table tbody tr:nth-child(11) { animation-delay: 0.40s; }
    .trash-table tbody tr:nth-child(12) { animation-delay: 0.44s; }
    .trash-table tbody tr:nth-child(13) { animation-delay: 0.48s; }
    .trash-table tbody tr:nth-child(14) { animation-delay: 0.52s; }
    .trash-table tbody tr:nth-child(15) { animation-delay: 0.56s; }

    /* ─── Responsive ─── */
    @media (max-width: 767px) {
        .trash-page { padding: 1.25rem 1rem 2rem; }
        .trash-title { font-size: 1.4rem; }
        .filter-card { padding: 1rem; }
    }
</style>
@endpush

@section('content')
<div class="trash-page">

    {{-- ── Header ── --}}
    <div class="trash-header">
        <div class="trash-header-left">
            <div class="trash-eyebrow">
                <i class="ri ri-delete-bin-3-line"></i> Recycle Bin
            </div>
            <h1 class="trash-title">Trashed Categories</h1>
            <p class="trash-subtitle">Restore or permanently remove archived categories</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn-back">
            <i class="ri ri-arrow-left-line"></i> Back to Categories
        </a>
    </div>

    {{-- ── Filter ── --}}
    <div class="filter-card">
        <form action="{{ route('admin.categories.trashed') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="filter-label">Search</label>
                    <div class="filter-input-wrap">
                        <i class="ri ri-search-line"></i>
                        <input type="text" name="search" class="filter-input" placeholder="Search by name…" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All Status</option>
                        @foreach(App\Enums\CategoryStatusEnum::cases() as $status)
                            <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn-filter">
                        <i class="ri ri-filter-3-line"></i> Apply Filter
                    </button>
                    <a href="{{ route('admin.categories.trashed') }}" class="btn-reset" title="Clear filters">
                        <i class="ri ri-refresh-line"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- ── Table ── --}}
    <div class="table-card">
        <div style="overflow-x: auto;">
            <table class="trash-table">
                <thead>
                    <tr>
                        <th style="width:48px">#</th>
                        <th>Category</th>
                        <th>Parent</th>
                        <th class="center">Status</th>
                        <th>Deleted</th>
                        <th class="center" style="width:100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                    <tr>
                        {{-- # --}}
                        <td><span class="row-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span></td>

                        {{-- Info --}}
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($category->images->first())
                                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="cat-avatar">
                                @else
                                    <div class="cat-avatar-placeholder">
                                        <i class="ri ri-image-line"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="cat-name">{{ $category->name }}</div>
                                    <div class="cat-slug">{{ $category->slug }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Parent --}}
                        <td>
                            <span class="badge-parent">
                                <i class="ri ri-folder-line"></i>
                                {{ $category->parent->name ?? 'Root' }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="center">
                            @php
                                $colorMap = [
                                    'success' => 'active',
                                    'danger'  => 'danger',
                                ];
                                $colorKey = $colorMap[$category->status->color()] ?? 'default';
                            @endphp
                            <span class="badge-status {{ $colorKey }}">
                                {{ $category->status->label() }}
                            </span>
                        </td>

                        {{-- Deleted At --}}
                        <td>
                            <div class="deleted-date">{{ $category->deleted_at->format('Y-m-d') }}</div>
                            <div class="deleted-ago">{{ $category->deleted_at->diffForHumans() }}</div>
                        </td>

                        {{-- Actions --}}
                        <td class="center">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- Restore --}}
                                <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="action-btn restore" title="Restore category">
                                        <i class="ri ri-restart-line"></i>
                                    </button>
                                </form>

                                {{-- Force Delete --}}
                                <form action="{{ route('admin.categories.force-delete', $category->id) }}" method="POST" class="delete-form">
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
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-icon-wrap">
                                    <i class="ri ri-delete-bin-line"></i>
                                </div>
                                <p class="empty-title">Trash is empty</p>
                                <p class="empty-subtitle">No trashed categories found matching your criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
        <div class="pagination-wrap">
            {{ $categories->links('pagination::bootstrap-5') }}
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
                html: '<span style="color:#9e9bb8;font-size:.9rem">This action <strong style="color:#e0364a">cannot be undone</strong>. The category will be removed forever.</span>',
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                scrollbarPadding: false,
                customClass: {
                    confirmButton: 'btn btn-danger me-3',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
@endpush