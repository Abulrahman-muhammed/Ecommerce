@extends('admin.layouts.master')

@section('title', 'Edit Customer')

@push('styles')
<style>
    :root {
        --accent:       #7367f0;
        --accent-soft:  rgba(115, 103, 240, 0.12);
        --accent-hover: #6254e8;
        --danger:       #ea5455;
        --success:      #28c76f;
        --warning:      #ff9f43;
        --info:         #00cfe8;
        --text-primary: #4b465c;
        --text-muted:   #a5a3ae;
        --border:       #dbdade;
        --bg-body:      #f8f7fa;
        --card-bg:      #ffffff;
        --radius:       0.5rem;
        --shadow:       0 0.25rem 1.125rem rgba(161, 172, 184, 0.42);
    }

    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
    .page-header h4 { font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin: 0; }
    .btn-back {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .8125rem; font-weight: 500; color: var(--text-primary);
        background: var(--card-bg); border: 1px solid var(--border);
        border-radius: var(--radius); padding: .4rem .9rem;
        text-decoration: none; transition: border-color .18s, color .18s, box-shadow .18s;
    }
    .btn-back:hover { border-color: var(--accent); color: var(--accent); box-shadow: 0 2px 8px var(--accent-soft); }

    /* ── Info pill (read-only provider info) ── */
    .info-strip {
        display: flex; gap: .75rem; flex-wrap: wrap;
        padding: .875rem 1.25rem;
        background: var(--bg-body); border: 1px solid var(--border);
        border-radius: var(--radius); margin-bottom: 1.5rem;
    }
    .info-pill {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .78rem; font-weight: 500; color: var(--text-primary);
        background: var(--card-bg); border: 1px solid var(--border);
        border-radius: 2rem; padding: .3rem .875rem;
    }
    .info-pill i { color: var(--text-muted); font-size: .875rem; }
    .info-pill.verified   { border-color: rgba(40,199,111,.3); background: rgba(40,199,111,.06); color: #28c76f; }
    .info-pill.unverified { border-color: rgba(234,84,85,.3);  background: rgba(234,84,85,.06);  color: #ea5455; }

    /* ── Avatar ── */
    .avatar-section {
        display: flex; align-items: center; gap: 1.25rem;
        padding: 1.25rem;
        background: var(--bg-body); border: 2px dashed var(--border);
        border-radius: var(--radius); margin-bottom: 1.5rem;
        transition: border-color .2s;
    }
    .avatar-section:hover { border-color: var(--accent); }
    .avatar-preview {
        width: 72px; height: 72px; border-radius: 50%; flex-shrink: 0;
        overflow: hidden; border: 3px solid rgba(115,103,240,.2);
        background: linear-gradient(135deg, #696cff, #9155fd);
        display: flex; align-items: center; justify-content: center;
    }
    .avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-placeholder { font-size: 1.75rem; color: #fff; font-weight: 700; }
    .avatar-meta { flex: 1; }
    .avatar-meta strong { font-size: .875rem; font-weight: 600; color: var(--text-primary); display: block; margin-bottom: .2rem; }
    .avatar-meta small { font-size: .76rem; color: var(--text-muted); }
    .btn-choose-avatar {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .8rem; font-weight: 500; color: var(--accent);
        background: var(--accent-soft); border: 1px solid rgba(115,103,240,.2);
        border-radius: var(--radius); padding: .35rem .875rem;
        cursor: pointer; transition: background .18s; margin-top: .5rem;
    }
    .btn-choose-avatar:hover { background: rgba(115,103,240,.2); }
    #avatar-file-input { display: none; }

    /* ── Card ── */
    .form-card {
        background: var(--card-bg); border: none;
        border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden;
    }
    .form-card .card-header {
        background: var(--bg-body); border-bottom: 1px solid var(--border);
        padding: 1rem 1.5rem; display: flex; align-items: center; gap: .6rem;
    }
    .form-card .card-header .header-icon {
        width: 32px; height: 32px; border-radius: .375rem;
        background: var(--accent-soft); color: var(--accent);
        display: flex; align-items: center; justify-content: center; font-size: 1rem;
    }
    .form-card .card-header span { font-size: .9rem; font-weight: 600; color: var(--text-primary); }
    .form-card .card-body { padding: 1.5rem; }

    .field-group { display: flex; flex-direction: column; gap: .4rem; }
    .f-label { font-size: .8125rem; font-weight: 500; color: var(--text-primary); margin: 0; }
    .f-label .required { color: var(--danger); margin-left: 2px; }
    .f-control {
        width: 100%; padding: .5rem .875rem; font-size: .875rem;
        color: var(--text-primary); background: var(--card-bg);
        border: 1px solid var(--border); border-radius: var(--radius);
        transition: border-color .18s, box-shadow .18s; appearance: none; -webkit-appearance: none;
    }
    .f-control::placeholder { color: var(--text-muted); }
    .f-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 .2rem var(--accent-soft); }
    .f-control.is-invalid { border-color: var(--danger); }
    .f-control.is-invalid:focus { box-shadow: 0 0 0 .2rem rgba(234,84,85,.12); }
    select.f-control {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%23a5a3ae' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right .875rem center;
        padding-right: 2.25rem; cursor: pointer;
    }
    .f-hint  { font-size: .76rem; color: var(--text-muted); margin: 0; }
    .f-error { font-size: .76rem; color: var(--danger);     margin: 0; }

    /* Password */
    .pw-wrap { position: relative; }
    .pw-wrap .f-control { padding-right: 2.5rem; }
    .pw-toggle {
        position: absolute; right: .75rem; top: 50%; transform: translateY(-50%);
        background: none; border: none; color: var(--text-muted);
        cursor: pointer; font-size: 1rem; padding: 0; transition: color .18s;
    }
    .pw-toggle:hover { color: var(--accent); }

    /* Section divider */
    .section-divider {
        display: flex; align-items: center; gap: .75rem;
        margin: 1.5rem 0 1.25rem;
    }
    .section-divider span {
        font-size: .6875rem; font-weight: 700; letter-spacing: .08em;
        text-transform: uppercase; color: var(--text-muted); white-space: nowrap;
    }
    .section-divider::before, .section-divider::after {
        content: ''; flex: 1; height: 1px; background: var(--border);
    }

    .form-divider { border: none; border-top: 1px solid var(--border); margin: 1.5rem 0 1.25rem; }

    /* Role radio */
    .role-radio-group { display: flex; gap: .75rem; flex-wrap: wrap; padding-top: .25rem; }
    .role-radio-group .radio-pill { position: relative; }
    .role-radio-group .radio-pill input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
    .role-radio-group .radio-pill label {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .375rem .875rem; font-size: .8125rem; font-weight: 500;
        color: var(--text-muted); border: 1px solid var(--border);
        border-radius: 2rem; cursor: pointer; user-select: none;
        transition: border-color .18s, color .18s, background .18s;
    }
    .role-radio-group .radio-pill label::before {
        content: ''; display: inline-block; width: 7px; height: 7px;
        border-radius: 50%; background: var(--text-muted); transition: background .18s;
    }
    .role-radio-group .radio-pill input:checked + label {
        border-color: var(--accent); color: var(--accent);
        background: var(--accent-soft); box-shadow: 0 2px 6px var(--accent-soft);
    }
    .role-radio-group .radio-pill input:checked + label::before { background: var(--accent); }

    /* Buttons */
    .btn-submit {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .5rem 1.5rem; font-size: .875rem; font-weight: 500;
        color: #fff; background: var(--accent); border: none;
        border-radius: var(--radius); cursor: pointer;
        transition: background .18s, box-shadow .18s, transform .1s;
        box-shadow: 0 4px 12px rgba(115,103,240,.35);
    }
    .btn-submit:hover { background: var(--accent-hover); box-shadow: 0 6px 16px rgba(115,103,240,.45); }
    .btn-submit:active { transform: translateY(1px); }
    .btn-cancel {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .5rem 1.25rem; font-size: .875rem; font-weight: 500;
        color: var(--text-primary); background: transparent;
        border: 1px solid var(--border); border-radius: var(--radius);
        text-decoration: none; transition: border-color .18s, color .18s;
    }
    .btn-cancel:hover { border-color: var(--text-primary); color: var(--text-primary); }

    /* Password note */
    .pw-note {
        display: flex; align-items: center; gap: .5rem;
        padding: .625rem .875rem;
        background: rgba(115,103,240,.06); border: 1px solid rgba(115,103,240,.15);
        border-radius: var(--radius); font-size: .78rem; color: var(--text-muted);
    }
    .pw-note i { color: var(--accent); flex-shrink: 0; }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Page Header --}}
    <div class="page-header">
        <h4>Edit Customer</h4>
        <a href="{{ route('admin.customers.index') }}" class="btn-back">
            <i class="ri ri-arrow-left-line"></i> Back to Customers
        </a>
    </div>

    {{-- Read-only info strip --}}
    <div class="info-strip">
        <span class="info-pill">
            <i class="ri ri-fingerprint-line"></i>
            ID: <strong>{{ $customer->id }}</strong>
        </span>
        <span class="info-pill {{ $customer->email_verified_at ? 'verified' : 'unverified' }}">
            <i class="ri ri-{{ $customer->email_verified_at ? 'shield-check' : 'shield-cross' }}-line"></i>
            {{ $customer->email_verified_at ? 'Email Verified' : 'Email Not Verified' }}
        </span>
        @if($customer->provider)
        <span class="info-pill">
            <i class="ri ri-links-line"></i>
            Provider: <strong>{{ ucfirst($customer->provider) }}</strong>
        </span>
        @endif
        <span class="info-pill">
            <i class="ri ri-calendar-line"></i>
            Joined: <strong>{{ $customer->created_at->format('M d, Y') }}</strong>
        </span>
    </div>

    <div class="card form-card">
        <div class="card-header">
            <div class="header-icon"><i class="ri ri-user-settings-line"></i></div>
            <span>Customer Details</span>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- ── Avatar ── --}}
                <div class="avatar-section">
                    <div class="avatar-preview" id="avatar-preview-wrap">
                        @if($customer->avatar)
                            <img src="{{ asset('storage/'.$customer->avatar) }}" alt="{{ $customer->name }}" id="avatar-preview-img">
                        @else
                            <img src="" alt="" id="avatar-preview-img" style="display:none">
                            <span class="avatar-placeholder" id="avatar-initials">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <div class="avatar-meta">
                        <strong>Profile Photo</strong>
                        <small>Upload a new photo to replace the current one.</small>
                        <br>
                        <label for="avatar-file-input" class="btn-choose-avatar">
                            <i class="ri ri-upload-2-line"></i> Choose New Photo
                        </label>
                        <input type="file" id="avatar-file-input" name="avatar" accept="image/*">
                    </div>
                </div>

                {{-- ── Basic Info ── --}}
                <div class="section-divider"><span>Basic Information</span></div>

                <div class="row g-4">

                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="f-label">Full Name <span class="required">*</span></label>
                            <input type="text" name="name"
                                   class="f-control @error('name') is-invalid @enderror"
                                   placeholder="e.g. Ahmed Hassan"
                                   value="{{ old('name', $customer->name) }}">
                            @error('name')
                                <p class="f-error">{{ $message }}</p>
                            @else
                                <p class="f-hint">Customer's full display name.</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="f-label">Email Address <span class="required">*</span></label>
                            <input type="email" name="email" readonly="{{ $customer->provider ? 'readonly' : '' }}"
                                class="f-control @error('email') is-invalid @enderror"
                                placeholder="e.g. ahmed@example.com"
                                value="{{ old('email', $customer->email) }}">
                            @error('email')
                                <p class="f-error">{{ $message }}</p>
                            @else
                                <p class="f-hint">Changing email will require re-verification.</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="f-label">Phone Number</label>
                            <input type="text" name="phone"
                                   class="f-control @error('phone') is-invalid @enderror"
                                   placeholder="e.g. +20 100 000 0000"
                                   value="{{ old('phone', $customer->phone) }}">
                            @error('phone')
                                <p class="f-error">{{ $message }}</p>
                            @else
                                <p class="f-hint">Optional. Include country code.</p>
                            @enderror
                        </div>
                    </div>

  

                    <div class="col-6">
                        <div class="field-group">
                            <label class="f-label">Address</label>
                            <input type="text" name="address"
                                   class="f-control @error('address') is-invalid @enderror"
                                   placeholder="e.g. 123 Main St, Cairo, Egypt"
                                   value="{{ old('address', $customer->address) }}">
                            @error('address')
                                <p class="f-error">{{ $message }}</p>
                            @else
                                <p class="f-hint">Shipping / billing address.</p>
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- ── Change Password ── --}}
                <div class="section-divider"><span>Change Password</span></div>

                <div class="pw-note mb-4">
                    <i class="ri ri-information-line"></i>
                    Leave password fields blank to keep the current password unchanged.
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="f-label">New Password</label>
                            <div class="pw-wrap">
                                <input type="password" name="password" id="pw-field"
                                       class="f-control @error('password') is-invalid @enderror"
                                       placeholder="Leave blank to keep current">
                                <button type="button" class="pw-toggle" onclick="togglePw('pw-field', this)">
                                    <i class="ri ri-eye-off-line"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="f-error">{{ $message }}</p>
                            @else
                                <p class="f-hint">At least 8 characters.</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="f-label">Confirm New Password</label>
                            <div class="pw-wrap">
                                <input type="password" name="password_confirmation" id="pw-confirm-field"
                                       class="f-control"
                                       placeholder="Re-enter new password">
                                <button type="button" class="pw-toggle" onclick="togglePw('pw-confirm-field', this)">
                                    <i class="ri ri-eye-off-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="form-divider">

                <div class="d-flex justify-content-end align-items-center gap-2">
                    <a href="{{ route('admin.customers.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit">
                        <i class="ri ri-save-line"></i> Update Customer
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.getElementById('avatar-file-input').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function (e) {
        const img = document.getElementById('avatar-preview-img');
        img.src = e.target.result;
        img.style.display = 'block';
        const initials = document.getElementById('avatar-initials');
        if (initials) initials.style.display = 'none';
    };
    reader.readAsDataURL(file);
});

function togglePw(id, btn) {
    const field = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('ri-eye-off-line', 'ri-eye-line');
    } else {
        field.type = 'password';
        icon.classList.replace('ri-eye-line', 'ri-eye-off-line');
    }
}
</script>
@endpush