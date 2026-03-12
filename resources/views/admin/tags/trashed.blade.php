@extends('admin.layouts.master')

@section('title', 'Trashed Tags')

@push('styles')
<style>
.tags-page { font-family: 'Public Sans', sans-serif; }

/* ── Page Header ── */
.page-header-card {
    background: linear-gradient(135deg, #ea5455 0%, #ff6b6b 100%);
    border-radius: .5rem;
    padding: 1.5rem 1.75rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(234,84,85,.35);
}
.page-header-card::before {
    content: '';
    position: absolute;
    top: -40px; right: -30px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,.07);
}
.page-header-card::after {
    content: '';
    position: absolute;
    bottom: -50px; right: 60px;
    width: 100px; height: 100px;
    border-radius: 50%;
    background: rgba(255,255,255,.05);
}
.page-title {
    font-size: 1.375rem; font-weight: 600;
    color: #fff; margin: 0 0 .25rem;
}
.page-breadcrumb {
    font-size: .8125rem; color: rgba(255,255,255,.75); margin: 0;
}
.page-breadcrumb a { color: rgba(255,255,255,.85); text-decoration: none; }
.page-breadcrumb a:hover { color: #fff; }
.header-actions {
    display: flex; gap: .625rem;
    align-items: center; flex-wrap: wrap;
    position: relative; z-index: 1;
}
.stat-chip {
    display: inline-flex; align-items: center; gap: .5rem;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 50px;
    padding: .3rem .875rem;
    font-size: .78rem; color: rgba(255,255,255,.9); font-weight: 500;
    position: relative; z-index: 1;
}
.stat-chip strong { color: #fff; font-weight: 700; }
.btn-header-ghost {
    display: inline-flex; align-items: center; gap: .375rem;
    font-size: .8125rem; font-weight: 500; color: rgba(255,255,255,.9);
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
    border-radius: .375rem; padding: .4375rem 1rem;
    text-decoration: none; transition: all .2s; backdrop-filter: blur(4px);
}
.btn-header-ghost:hover {
    background: rgba(255,255,255,.25); color: #fff; transform: translateY(-1px);
}
.btn-header-danger {
    display: inline-flex; align-items: center; gap: .375rem;
    font-size: .8125rem; font-weight: 600; color: #ea5455;
    background: #fff; border: none; border-radius: .375rem;
    padding: .4375rem 1rem; text-decoration: none; transition: all .2s;
    box-shadow: 0 2px 8px rgba(0,0,0,.12); cursor: pointer;
}
.btn-header-danger:hover {
    color: #c0392b;
    box-shadow: 0 4px 14px rgba(0,0,0,.18);
    transform: translateY(-2px);
}

/* ── Notice Banner ── */
.trash-notice {
    display: flex; align-items: flex-start; gap: .75rem;
    background: rgba(234,84,85,.06);
    border: 1px solid rgba(234,84,85,.2);
    border-radius: .5rem;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
    font-size: .8125rem; color: #4b465c;
}
.trash-notice i { color: #ea5455; font-size: 1.1rem; margin-top: 1px; flex-shrink: 0; }
.trash-notice strong { color: #ea5455; }

/* ── Table Card ── */
.table-wrapper {
    background: #fff;
    border-radius: .5rem;
    border: 1px solid rgba(75,70,92,.08);
    box-shadow: 0 2px 6px rgba(75,70,92,.06);
    overflow: hidden;
}
.table-header {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(75,70,92,.07);
}
.table-header-title { font-size: .9375rem; font-weight: 600; color: #4b465c; }
.table-count-badge {
    display: inline-flex; align-items: center;
    background: rgba(234,84,85,.1); color: #ea5455;
    border-radius: 50px; font-size: .6875rem;
    font-weight: 700; padding: 2px 10px; margin-left: .5rem;
}

/* ── Table ── */
.mat-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
.mat-table thead tr {
    background: #fafafa;
    border-bottom: 1px solid rgba(75,70,92,.08);
}
.mat-table th {
    font-family: 'Public Sans', sans-serif;
    font-size: .6875rem; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    color: #a8aaae; padding: .875rem 1.25rem; white-space: nowrap;
}
.mat-table th.center,
.mat-table td.center { text-align: center; }
.mat-table tbody tr {
    border-bottom: 1px solid rgba(75,70,92,.06);
    transition: background .15s;
}
.mat-table tbody tr:last-child { border-bottom: none; }
.mat-table tbody tr:hover { background: rgba(234,84,85,.02); }
.mat-table td { padding: .9375rem 1.25rem; color: #4b465c; vertical-align: middle; }

/* ── Cells ── */
.row-num { font-size: .75rem; font-weight: 600; color: #b7b5be; }
.tag-name-wrap {
    display: inline-flex; align-items: center; gap: .5rem;
}
.tag-icon {
    width: 36px; height: 36px; border-radius: .375rem;
    background: rgba(234,84,85,.08);
    display: flex; align-items: center; justify-content: center;
    color: #ea5455; font-size: 1rem; flex-shrink: 0;
    border: 1px solid rgba(234,84,85,.15);
}
.tag-name {
    font-weight: 600; color: #a8aaae; font-size: .875rem;
    text-decoration: line-through;
}
.tag-pill {
    display: inline-flex; align-items: center; gap: .3rem;
    background: rgba(234,84,85,.07); color: #ea5455;
    border: 1px solid rgba(234,84,85,.15); border-radius: 50px;
    font-size: .75rem; font-weight: 600; padding: 3px 12px;
    font-family: 'Courier New', monospace;
}
.date-text {
    display: flex; align-items: center; gap: .3rem;
    font-size: .8125rem; color: #a8aaae; white-space: nowrap;
}
.date-deleted {
    display: flex; align-items: center; gap: .3rem;
    font-size: .8125rem; color: #ea5455; white-space: nowrap;
}

/* ── Action Buttons ── */
.act-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; border-radius: .375rem;
    border: none; background: transparent; cursor: pointer;
    transition: all .18s; font-size: .9375rem;
    text-decoration: none; color: #6d6d6d;
}
.act-btn:hover               { background: rgba(75,70,92,.08); }
.act-btn.act-restore:hover   { background: rgba(40,199,111,.1);  color: #28c76f; }
.act-btn.act-delete:hover    { background: rgba(234,84,85,.1);   color: #ea5455; }

/* ── Empty State ── */
.empty-state { padding: 3.5rem 2rem; text-align: center; }
.empty-icon {
    width: 64px; height: 64px; border-radius: .5rem;
    background: rgba(40,199,111,.08);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem; font-size: 1.75rem; color: #28c76f;
}
.empty-title { font-size: 1rem; font-weight: 600; color: #4b465c; margin: 0 0 .375rem; }
.empty-sub   { font-size: .875rem; color: #a8aaae; margin: 0; }

/* ── Pagination ── */
.pagi-wrap {
    padding: .875rem 1.5rem;
    border-top: 1px solid rgba(75,70,92,.07);
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap; gap: .5rem;
}
.pagi-info { font-size: .8125rem; color: #a8aaae; }
.pagi-wrap .pagination .page-link {
    background: #fff; border-color: rgba(75,70,92,.12);
    color: #4b465c; font-size: .8125rem; border-radius: .375rem !important;
    margin: 0 2px; transition: all .15s; padding: .3125rem .625rem;
    min-width: 32px; text-align: center;
}
.pagi-wrap .pagination .page-link:hover {
    background: rgba(234,84,85,.08);
    border-color: #ea5455; color: #ea5455;
}
.pagi-wrap .pagination .page-item.active .page-link {
    background: #ea5455; border-color: #ea5455; color: #fff;
    box-shadow: 0 2px 8px rgba(234,84,85,.4);
}

/* ── SweetAlert ── */
html.swal2-shown, body.swal2-shown { padding-right: 0 !important; overflow: auto !important; }
.swal2-container { z-index: 9999 !important; }
.swal2-popup {
    font-family: 'Public Sans', sans-serif !important;
    border-radius: .5rem !important;
    box-shadow: 0 8px 40px rgba(75,70,92,.18) !important;
}
.swal2-title { font-size: 1.125rem !important; font-weight: 600 !important; color: #4b465c !important; }
.swal2-html-container { font-size: .875rem !important; color: #a8aaae !important; }

/* ── Row Animations ── */
@keyframes rowFade {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.mat-table tbody tr { animation: rowFade .28s ease both; }
.mat-table tbody tr:nth-child(1)  { animation-delay: .02s; }
.mat-table tbody tr:nth-child(2)  { animation-delay: .06s; }
.mat-table tbody tr:nth-child(3)  { animation-delay: .10s; }
.mat-table tbody tr:nth-child(4)  { animation-delay: .14s; }
.mat-table tbody tr:nth-child(5)  { animation-delay: .18s; }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y tags-page">

    {{-- ── Page Header ── --}}
    <div class="page-header-card">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="page-title">
                    <i class="ri ri-delete-bin-line me-2"></i>Trashed Tags
                </h4>
                <p class="page-breadcrumb mb-2">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    <a href="{{ route('admin.tags.index') }}">Tags</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    Trash
                </p>
                <div class="d-flex gap-2 flex-wrap mt-1">
                    <span class="stat-chip">
                        <i class="ri ri-delete-bin-line"></i>
                        Trashed: <strong>{{ $tags->total() }}</strong>
                    </span>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.tags.index') }}" class="btn-header-ghost">
                    <i class="ri ri-arrow-left-line"></i> Back to Tags
                </a>
                @if($tags->total() > 0)
                <form action="{{ route('admin.tags.emptyTrash') }}" method="POST" id="emptyTrashForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-header-danger" id="emptyTrashBtn">
                        <i class="ri ri-delete-bin-2-line"></i> Empty Trash
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Alert ── --}}
    <x-alert/>

    {{-- ── Notice ── --}}
    <div class="trash-notice">
        <i class="ri ri-information-line"></i>
        <span>
            Tags in the trash are <strong>soft-deleted</strong> and not visible to customers.
            You can restore them or permanently delete them from here.
        </span>
    </div>

    {{-- ── Table ── --}}
    <div class="table-wrapper">
        <div class="table-header">
            <div class="table-header-title">
                Trashed Tags
                <span class="table-count-badge">{{ $tags->total() }}</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="mat-table">
                <thead>
                    <tr>
                        <th style="width:52px">#</th>
                        <th>Tag</th>
                        <th>Slug</th>
                        <th>Created</th>
                        <th>Deleted</th>
                        <th class="center" style="width:90px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tags as $tag)
                    <tr>

                        {{-- # --}}
                        <td>
                            <span class="row-num">
                                {{ str_pad(($tags->currentPage() - 1) * $tags->perPage() + $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

                        {{-- Tag --}}
                        <td>
                            <div class="tag-name-wrap">
                                <div class="tag-icon">
                                    <i class="ri ri-price-tag-3-line"></i>
                                </div>
                                <span class="tag-name">{{ $tag->name }}</span>
                            </div>
                        </td>

                        {{-- Slug --}}
                        <td>
                            <span class="tag-pill">
                                <i class="ri ri-links-line" style="font-size:.7rem"></i>
                                {{ $tag->slug }}
                            </span>
                        </td>

                        {{-- Created --}}
                        <td>
                            <span class="date-text">
                                <i class="ri ri-time-line"></i>
                                {{ $tag->created_at->format('M d, Y') }}
                            </span>
                        </td>

                        {{-- Deleted At --}}
                        <td>
                            <span class="date-deleted">
                                <i class="ri ri-delete-bin-line"></i>
                                {{ $tag->deleted_at->diffForHumans() }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="center">
                            <div class="d-flex justify-content-center gap-1">

                                {{-- Restore --}}
                                <form action="{{ route('admin.tags.restore', $tag->id) }}"
                                      method="POST" class="restore-form d-inline m-0 p-0">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button"
                                            class="act-btn act-restore restore-btn"
                                            title="Restore">
                                        <i class="ri ri-arrow-go-back-line"></i>
                                    </button>
                                </form>

                                {{-- Force Delete --}}
                                <form action="{{ route('admin.tags.forceDelete', $tag->id) }}"
                                      method="POST" class="force-delete-form d-inline m-0 p-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="act-btn act-delete force-delete-btn"
                                            title="Delete Permanently">
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
                                <div class="empty-icon">
                                    <i class="ri ri-checkbox-circle-line"></i>
                                </div>
                                <p class="empty-title">Trash is empty</p>
                                <p class="empty-sub">No deleted tags found. All clear!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tags->hasPages())
        <div class="pagi-wrap">
            <span class="pagi-info">
                Showing {{ $tags->firstItem() }}–{{ $tags->lastItem() }}
                of {{ $tags->total() }} entries
            </span>
            {{ $tags->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Restore
document.querySelectorAll('.restore-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const form = this.closest('.restore-form');
        Swal.fire({
            title: 'Restore Tag?',
            html: `<p style="color:#a8aaae;font-size:.875rem;margin:0">
                       This tag will be <strong style="color:#28c76f">restored</strong>
                       and become active again.
                   </p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="ri ri-arrow-go-back-line me-1"></i> Yes, Restore',
            cancelButtonText: 'Cancel',
            backdrop: true,
            allowOutsideClick: false,
            scrollbarPadding: false,
            didOpen: () => { document.body.style.paddingRight = '0px'; },
            customClass: {
                confirmButton: 'btn btn-success me-2 waves-effect waves-light',
                cancelButton:  'btn btn-label-secondary waves-effect',
            },
            buttonsStyling: false,
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });
});

// Force Delete
document.querySelectorAll('.force-delete-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const form = this.closest('.force-delete-form');
        Swal.fire({
            title: 'Delete Permanently?',
            html: `<p style="color:#a8aaae;font-size:.875rem;margin:0">
                       This action <strong style="color:#ea5455">cannot be undone</strong>.
                       The tag will be removed forever.
                   </p>`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: '<i class="ri ri-delete-bin-2-line me-1"></i> Delete Forever',
            cancelButtonText: 'Cancel',
            backdrop: true,
            allowOutsideClick: false,
            scrollbarPadding: false,
            didOpen: () => { document.body.style.paddingRight = '0px'; },
            customClass: {
                confirmButton: 'btn btn-danger me-2 waves-effect waves-light',
                cancelButton:  'btn btn-label-secondary waves-effect',
            },
            buttonsStyling: false,
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });
});

// Empty Trash
const emptyTrashBtn = document.getElementById('emptyTrashBtn');
if (emptyTrashBtn) {
    emptyTrashBtn.addEventListener('click', function () {
        Swal.fire({
            title: 'Empty Entire Trash?',
            html: `<p style="color:#a8aaae;font-size:.875rem;margin:0">
                       All trashed tags will be <strong style="color:#ea5455">permanently deleted</strong>.
                       This action cannot be reversed.
                   </p>`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: '<i class="ri ri-delete-bin-2-line me-1"></i> Empty Trash',
            cancelButtonText: 'Cancel',
            backdrop: true,
            allowOutsideClick: false,
            scrollbarPadding: false,
            didOpen: () => { document.body.style.paddingRight = '0px'; },
            customClass: {
                confirmButton: 'btn btn-danger me-2 waves-effect waves-light',
                cancelButton:  'btn btn-label-secondary waves-effect',
            },
            buttonsStyling: false,
        }).then(result => {
            if (result.isConfirmed) document.getElementById('emptyTrashForm').submit();
        });
    });
}
</script>
@endpush