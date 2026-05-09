@extends('admin.layouts.master')

@section('title', 'Customers')

@push('styles')
<style>
.customers-page { font-family: 'Public Sans', sans-serif; }

/* ── Page Header ── */
.page-header-card {
    background: linear-gradient(135deg, #696cff 0%, #9155fd 100%);
    border-radius: 0.5rem;
    padding: 1.5rem 1.75rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(105, 108, 255, .35);
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
.page-header-card .page-title {
    font-size: 1.375rem;
    font-weight: 600;
    color: #fff;
    margin: 0 0 .25rem;
}
.page-header-card .page-breadcrumb {
    font-size: .8125rem;
    color: rgba(255,255,255,.75);
    margin: 0;
}
.page-header-card .page-breadcrumb a {
    color: rgba(255,255,255,.85);
    text-decoration: none;
}
.page-header-card .page-breadcrumb a:hover { color: #fff; }
.page-header-card .header-actions {
    display: flex;
    gap: .625rem;
    align-items: center;
    flex-wrap: wrap;
    position: relative;
    z-index: 1;
}
.stat-chip {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 50px;
    padding: .3rem .875rem;
    font-size: .78rem;
    color: rgba(255,255,255,.9);
    font-weight: 500;
    position: relative;
    z-index: 1;
}
.stat-chip strong { color: #fff; font-weight: 700; }
.btn-header-ghost {
    display: inline-flex;
    align-items: center;
    gap: .375rem;
    font-size: .8125rem;
    font-weight: 500;
    color: rgba(255,255,255,.9);
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: .375rem;
    padding: .4375rem 1rem;
    text-decoration: none;
    transition: all .2s;
    backdrop-filter: blur(4px);
}
.btn-header-ghost:hover { background: rgba(255,255,255,.25); color: #fff; transform: translateY(-1px); }
.btn-header-primary {
    display: inline-flex;
    align-items: center;
    gap: .375rem;
    font-size: .8125rem;
    font-weight: 600;
    color: #696cff;
    background: #fff;
    border: none;
    border-radius: .375rem;
    padding: .4375rem 1rem;
    text-decoration: none;
    transition: all .2s;
    box-shadow: 0 2px 8px rgba(0,0,0,.12);
}
.btn-header-primary:hover { color: #5f61e6; box-shadow: 0 4px 14px rgba(0,0,0,.18); transform: translateY(-2px); }

/* ── Filter Card ── */
.filter-wrapper {
    background: #fff;
    border-radius: .5rem;
    border: 1px solid rgba(75, 70, 92, .08);
    box-shadow: 0 2px 6px rgba(75, 70, 92, .06);
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
}
.filter-title {
    font-size: .6875rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: #a8aaae;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: .375rem;
}
.filter-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(75,70,92,.08);
}
.form-label-sm {
    font-size: .75rem;
    font-weight: 600;
    color: #4b465c;
    margin-bottom: .375rem;
    display: block;
}
.mat-input {
    width: 100%;
    background: #fafafa;
    border: 1px solid rgba(75,70,92,.12);
    border-radius: .375rem;
    color: #4b465c;
    font-family: 'Public Sans', sans-serif;
    font-size: .875rem;
    padding: .4375rem .875rem;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.mat-input::placeholder { color: #a8aaae; }
.mat-input:focus { border-color: #696cff; box-shadow: 0 0 0 3px rgba(105,108,255,.12); }
.input-icon-wrap { position: relative; }
.input-icon-wrap .input-icon {
    position: absolute; left: .75rem; top: 50%;
    transform: translateY(-50%);
    color: #a8aaae; font-size: .9375rem; pointer-events: none;
}
.input-icon-wrap .mat-input { padding-left: 2.125rem; }
.mat-select {
    width: 100%;
    background: #fafafa;
    border: 1px solid rgba(75,70,92,.12);
    border-radius: .375rem;
    color: #4b465c;
    font-family: 'Public Sans', sans-serif;
    font-size: .875rem;
    padding: .4375rem .875rem;
    outline: none;
    appearance: none;
    cursor: pointer;
    transition: border-color .2s, box-shadow .2s;
}
.mat-select:focus { border-color: #696cff; box-shadow: 0 0 0 3px rgba(105,108,255,.12); }
.select-wrap { position: relative; }
.select-wrap::after {
    content: '\ea4e'; font-family: 'remixicon';
    position: absolute; right: .75rem; top: 50%;
    transform: translateY(-50%);
    color: #a8aaae; font-size: .875rem; pointer-events: none;
}
.btn-mat-primary {
    display: inline-flex; align-items: center; justify-content: center;
    gap: .375rem;
    font-family: 'Public Sans', sans-serif;
    font-size: .8125rem; font-weight: 600;
    color: #fff; background: #696cff;
    border: none; border-radius: .375rem;
    padding: .4375rem 1.125rem;
    cursor: pointer; transition: all .2s;
    box-shadow: 0 2px 8px rgba(105,108,255,.35); flex: 1;
}
.btn-mat-primary:hover { background: #5f61e6; box-shadow: 0 4px 14px rgba(105,108,255,.45); transform: translateY(-1px); }
.btn-mat-outline {
    display: inline-flex; align-items: center; justify-content: center;
    font-family: 'Public Sans', sans-serif;
    font-size: .875rem; color: #6d6d6d;
    background: #fff; border: 1px solid rgba(75,70,92,.2);
    border-radius: .375rem; padding: .4375rem .8125rem;
    text-decoration: none; transition: all .2s;
}
.btn-mat-outline:hover { border-color: #696cff; color: #696cff; background: rgba(105,108,255,.06); }

/* ── Table Card ── */
.table-wrapper {
    background: #fff;
    border-radius: .5rem;
    border: 1px solid rgba(75,70,92,.08);
    box-shadow: 0 2px 6px rgba(75,70,92,.06);
    overflow: hidden;
}
.table-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(75,70,92,.07);
}
.table-header-title { font-size: .9375rem; font-weight: 600; color: #4b465c; }
.table-count-badge {
    display: inline-flex; align-items: center;
    background: rgba(105,108,255,.1); color: #696cff;
    border-radius: 50px; font-size: .6875rem; font-weight: 700;
    padding: 2px 10px; margin-left: .5rem;
}
.mat-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
.mat-table thead tr { background: #fafafa; border-bottom: 1px solid rgba(75,70,92,.08); }
.mat-table th {
    font-family: 'Public Sans', sans-serif;
    font-size: .6875rem; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    color: #a8aaae; padding: .875rem 1.25rem; white-space: nowrap;
}
.mat-table th.center, .mat-table td.center { text-align: center; }
.mat-table tbody tr { border-bottom: 1px solid rgba(75,70,92,.06); transition: background .15s; }
.mat-table tbody tr:last-child { border-bottom: none; }
.mat-table tbody tr:hover { background: rgba(105,108,255,.03); }
.mat-table td { padding: .9375rem 1.25rem; color: #4b465c; vertical-align: middle; }
.row-num { font-size: .75rem; font-weight: 600; color: #b7b5be; }

/* ── Customer Cell ── */
.user-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    object-fit: cover; border: 2px solid rgba(105,108,255,.2); flex-shrink: 0;
}
.user-avatar-placeholder {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg, #696cff, #9155fd);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .875rem; font-weight: 600;
    flex-shrink: 0; border: 2px solid rgba(105,108,255,.2);
}
.user-name { font-weight: 600; color: #4b465c; font-size: .875rem; line-height: 1.3; }
.user-email { font-size: .78rem; color: #a8aaae; margin-top: 1px; }

/* ── Badges ── */
.role-badge {
    display: inline-flex; align-items: center; gap: .25rem;
    font-size: .6875rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase;
    border-radius: .25rem; padding: 3px 9px;
}
.role-admin   { background: rgba(105,108,255,.1); color: #696cff; border: 1px solid rgba(105,108,255,.2); }
.role-store   { background: rgba(255,159,67,.1);  color: #ff9f43; border: 1px solid rgba(255,159,67,.2); }
.role-user    { background: rgba(168,170,174,.1); color: #a8aaae; border: 1px solid rgba(168,170,174,.2); }

.provider-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .75rem; font-weight: 500;
    background: rgba(75,70,92,.06); border: 1px solid rgba(75,70,92,.1);
    border-radius: .25rem; padding: 2px 8px; color: #6d6d6d;
}
.provider-google { background: rgba(234,67,53,.07); border-color: rgba(234,67,53,.15); color: #ea4335; }
.provider-facebook { background: rgba(24,119,242,.07); border-color: rgba(24,119,242,.15); color: #1877f2; }
.provider-github { background: rgba(36,41,47,.07); border-color: rgba(36,41,47,.15); color: #24292f; }

.verified-yes {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .6875rem; font-weight: 700; letter-spacing: .04em;
    text-transform: uppercase;
    background: rgba(40,199,111,.12); color: #28c76f;
    border-radius: .25rem; padding: 3px 9px;
}
.verified-no {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .6875rem; font-weight: 700; letter-spacing: .04em;
    text-transform: uppercase;
    background: rgba(234,84,85,.1); color: #ea5455;
    border-radius: .25rem; padding: 3px 9px;
}

.store-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .75rem; font-weight: 600;
    background: rgba(0,207,232,.08); color: #00cfe8;
    border: 1px solid rgba(0,207,232,.2); border-radius: .25rem;
    padding: 3px 9px;
}
.no-store { color: #c4c4c4; font-weight: 700; font-size: 1rem; }

.date-text {
    display: flex; align-items: center; gap: .3rem;
    font-size: .8125rem; color: #a8aaae;
}

/* ── Action Buttons ── */
.act-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px;
    border-radius: .375rem; border: none;
    background: transparent; cursor: pointer;
    transition: all .18s; font-size: .9375rem;
    text-decoration: none; color: #6d6d6d;
}
.act-btn:hover { background: rgba(75,70,92,.08); }
.act-btn.act-view:hover   { background: rgba(0,207,232,.1);   color: #00cfe8; }
.act-btn.act-edit:hover   { background: rgba(105,108,255,.1); color: #696cff; }
.act-btn.act-delete:hover { background: rgba(234,84,85,.1);   color: #ea5455; }

/* ── Empty State ── */
.empty-state { padding: 3.5rem 2rem; text-align: center; }
.empty-icon {
    width: 64px; height: 64px; border-radius: .5rem;
    background: rgba(105,108,255,.08);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem; font-size: 1.75rem; color: #696cff;
}
.empty-title { font-size: 1rem; font-weight: 600; color: #4b465c; margin: 0 0 .375rem; }
.empty-sub   { font-size: .875rem; color: #a8aaae; margin: 0; }

/* ── Pagination ── */
.pagi-wrap {
    padding: .875rem 1.5rem;
    border-top: 1px solid rgba(75,70,92,.07);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .5rem;
}
.pagi-info { font-size: .8125rem; color: #a8aaae; }
.pagi-wrap .pagination .page-link {
    background: #fff; border-color: rgba(75,70,92,.12);
    color: #4b465c; font-size: .8125rem;
    border-radius: .375rem !important;
    margin: 0 2px; transition: all .15s;
    padding: .3125rem .625rem; min-width: 32px; text-align: center;
}
.pagi-wrap .pagination .page-link:hover { background: rgba(105,108,255,.08); border-color: #696cff; color: #696cff; }
.pagi-wrap .pagination .page-item.active .page-link {
    background: #696cff; border-color: #696cff; color: #fff;
    box-shadow: 0 2px 8px rgba(105,108,255,.4);
}

html.swal2-shown, body.swal2-shown { padding-right: 0 !important; overflow: auto !important; }
.swal2-container { z-index: 9999 !important; }
.swal2-popup { font-family: 'Public Sans', sans-serif !important; border-radius: .5rem !important; box-shadow: 0 8px 40px rgba(75,70,92,.18) !important; }
.swal2-title { font-size: 1.125rem !important; font-weight: 600 !important; color: #4b465c !important; }
.swal2-html-container { font-size: .875rem !important; color: #a8aaae !important; }

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
.mat-table tbody tr:nth-child(6)  { animation-delay: .22s; }
.mat-table tbody tr:nth-child(7)  { animation-delay: .26s; }
.mat-table tbody tr:nth-child(8)  { animation-delay: .30s; }
.mat-table tbody tr:nth-child(9)  { animation-delay: .34s; }
.mat-table tbody tr:nth-child(10) { animation-delay: .38s; }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y customers-page">

    {{-- ── Page Header ── --}}
    <div class="page-header-card">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="page-title">
                    <i class="ri ri-group-line me-2"></i>Customers
                </h4>
                <p class="page-breadcrumb mb-2">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    Manage Customers
                </p>
                <div class="d-flex gap-2 flex-wrap mt-1">
                    <span class="stat-chip">
                        <i class="ri ri-user-3-line"></i>
                        Total: <strong>{{ $customers->total() }}</strong>
                    </span>
                    <span class="stat-chip">
                        <i class="ri ri-checkbox-circle-line"></i>
                        Showing: <strong>{{ $customers->count() }}</strong>
                    </span>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.customers.trashed') }}" class="btn-header-ghost">
                    <i class="ri ri-delete-bin-line"></i> Trashed
                </a>
            </div>
        </div>
    </div>

    {{-- Alert --}}
    <x-alert/>

    {{-- ── Filter Card ── --}}
    <div class="filter-wrapper">
        <div class="filter-title">
            <i class="ri ri-equalizer-line" style="color:#a8aaae"></i>
            Filters
        </div>
        <form action="{{ route('admin.customers.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label-sm">Search</label>
                    <div class="input-icon-wrap">
                        <i class="ri ri-search-line input-icon"></i>
                        <input type="text" name="search" class="mat-input"
                               placeholder="Search by name, email or phone…"
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label-sm">Verified</label>
                    <div class="select-wrap">
                        <select name="verified" class="mat-select">
                            <option value="">All</option>
                            <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Verified</option>
                            <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>Unverified</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label-sm">Provider</label>
                    <div class="select-wrap">
                        <select name="provider" class="mat-select">
                            <option value="">All Providers</option>
                            <option value="local"    {{ request('provider') == 'local'    ? 'selected' : '' }}>Local</option>
                            <option value="google"   {{ request('provider') == 'google'   ? 'selected' : '' }}>Google</option>
                            <option value="facebook" {{ request('provider') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                            <option value="github"   {{ request('provider') == 'github'   ? 'selected' : '' }}>GitHub</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn-mat-primary">
                        <i class="ri ri-filter-3-line"></i> Apply
                    </button>
                    <a href="{{ route('admin.customers.index') }}" class="btn-mat-outline" title="Reset">
                        <i class="ri ri-refresh-line"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- ── Table Card ── --}}
    <div class="table-wrapper">
        <div class="table-header">
            <div class="table-header-title">
                All Customers
                <span class="table-count-badge">{{ $customers->total() }}</span>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="mat-table">
                <thead>
                    <tr>
                        <th style="width:52px">#</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th class="center">Verified</th>
                        <th>Provider</th>
                        <th>Joined</th>
                        <th class="center" style="width:110px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                    <tr>
                        {{-- # --}}
                        <td>
                            <span class="row-num">
                                {{ str_pad(($customers->currentPage()-1)*$customers->perPage()+$loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

<td>
    <div class="d-flex align-items-center gap-3">
        @if($customer->avatar)

            @if(filter_var($customer->avatar, FILTER_VALIDATE_URL))
                <img src="{{ $customer->avatar }}"
                    alt="{{ $customer->name }}"
                    class="user-avatar">
            @else
                <img src="{{ asset('storage/'.$customer->avatar) }}"
                    alt="{{ $customer->name }}"
                    class="user-avatar">
            @endif

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

                        {{-- Phone --}}
                        <td>
                            @if($customer->phone)
                                <span style="font-size:.875rem;color:#4b465c;">{{ $customer->phone }}</span>
                            @else
                                <span style="color:#c4c4c4;font-weight:700;">—</span>
                            @endif
                        </td>



                        {{-- Verified --}}
                        <td class="center">
                            @if($customer->email_verified_at)
                                <span class="verified-yes">
                                    <i class="ri ri-shield-check-line"></i> Yes
                                </span>
                            @else
                                <span class="verified-no">
                                    <i class="ri ri-shield-cross-line"></i> No
                                </span>
                            @endif
                        </td>

                        {{-- Provider --}}
                        <td>
                            @php
                                $provider = $customer->provider ?? 'local';
                                $providerClass = match($provider) {
                                    'google'   => 'provider-google',
                                    'facebook' => 'provider-facebook',
                                    'github'   => 'provider-github',
                                    default    => '',
                                };
                                $providerIcon = match($provider) {
                                    'google'   => 'ri-google-line',
                                    'facebook' => 'ri-facebook-circle-line',
                                    'github'   => 'ri-github-line',
                                    default    => 'ri-lock-password-line',
                                };
                            @endphp
                            <span class="provider-badge {{ $providerClass }}">
                                <i class="{{ $providerIcon }}"></i>
                                {{ ucfirst($provider) }}
                            </span>
                        </td>



                        {{-- Joined --}}
                        <td>
                            <span class="date-text">
                                <i class="ri ri-time-line"></i>
                                {{ $customer->created_at->diffForHumans() }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.customers.show', $customer->id) }}"
                                   class="act-btn act-view" title="View">
                                    <i class="ri ri-eye-line"></i>
                                </a>
                                <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                   class="act-btn act-edit" title="Edit">
                                    <i class="ri ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('admin.customers.destroy', $customer->id) }}"
                                      method="POST" class="delete-form d-inline m-0 p-0">
                                    @csrf @method('DELETE')
                                    <button type="button" class="act-btn act-delete delete-btn" title="Delete">
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
                                <div class="empty-icon"><i class="ri ri-user-unfollow-line"></i></div>
                                <p class="empty-title">No customers found</p>
                                <p class="empty-sub">Try adjusting your filters or add a new customer</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
        <div class="pagi-wrap">
            <span class="pagi-info">
                Showing {{ $customers->firstItem() }}–{{ $customers->lastItem() }} of {{ $customers->total() }} entries
            </span>
            {{ $customers->links('pagination::bootstrap-5') }}
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