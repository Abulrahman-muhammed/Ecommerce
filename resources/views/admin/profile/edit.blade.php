{{-- resources/views/admin/profile/edit.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'My Profile')

@push('styles')
<style>
.profile-page { font-family: 'Public Sans', sans-serif; }

/* ── Page Header ─────────────────────────────────── */
.page-header-card {
    background: linear-gradient(135deg, #696cff 0%, #9155fd 100%);
    border-radius: .5rem; padding: 1.5rem 1.75rem;
    margin-bottom: 1.5rem; position: relative;
    overflow: hidden; box-shadow: 0 4px 18px rgba(105,108,255,.35);
}
.page-header-card::before {
    content: ''; position: absolute; top: -40px; right: -30px;
    width: 160px; height: 160px; border-radius: 50%; background: rgba(255,255,255,.07);
}
.page-header-card::after {
    content: ''; position: absolute; bottom: -50px; right: 60px;
    width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,.05);
}
.page-title      { font-size: 1.375rem; font-weight: 600; color: #fff; margin: 0 0 .25rem; }
.page-breadcrumb { font-size: .8125rem; color: rgba(255,255,255,.75); margin: 0; }
.page-breadcrumb a { color: rgba(255,255,255,.85); text-decoration: none; }
.page-breadcrumb a:hover { color: #fff; }

/* ── Hero Card ───────────────────────────────────── */
.hero-card {
    background: #fff; border: 1px solid rgba(75,70,92,.08);
    border-radius: .5rem; box-shadow: 0 2px 6px rgba(75,70,92,.06);
    padding: 1.5rem 1.75rem; margin-bottom: 1.5rem;
    display: flex; align-items: center; gap: 1.25rem;
    position: relative; overflow: hidden;
}
.hero-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; background: linear-gradient(90deg, #696cff, #9155fd);
}
.hero-avatar-wrap { position: relative; flex-shrink: 0; }
.hero-avatar {
    width: 72px; height: 72px; border-radius: 50%;
    object-fit: cover; border: 3px solid rgba(105,108,255,.2);
}
.hero-avatar-status {
    position: absolute; bottom: 3px; right: 3px;
    width: 13px; height: 13px; border-radius: 50%;
    background: #28c76f; border: 2px solid #fff;
}
.hero-name { font-size: 1.125rem; font-weight: 700; color: #4b465c; margin: 0 0 .15rem; }
.hero-role {
    font-size: .6875rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: #696cff; margin: 0 0 .625rem;
}
.hero-chips { display: flex; flex-wrap: wrap; gap: .4rem; }
.hero-chip {
    display: inline-flex; align-items: center; gap: .3rem;
    background: rgba(75,70,92,.04); border: 1px solid rgba(75,70,92,.1);
    border-radius: 50px; padding: .2rem .7rem;
    font-size: .75rem; color: #6d6d6d;
}
.hero-chip i { font-size: .75rem; color: #696cff; }

/* ── Section Card ────────────────────────────────── */
.sec-card {
    background: #fff; border: 1px solid rgba(75,70,92,.08);
    border-radius: .5rem; box-shadow: 0 2px 6px rgba(75,70,92,.06);
    overflow: hidden; margin-bottom: 1.5rem;
}
.sec-card-header {
    display: flex; align-items: center;
    padding: .875rem 1.25rem; border-bottom: 1px solid rgba(75,70,92,.07);
}
.sec-card-title {
    font-size: .875rem; font-weight: 700; color: #4b465c;
    display: flex; align-items: center; gap: .5rem; margin: 0; flex: 1;
}
.sec-card-title i { color: #696cff; font-size: 1rem; }
.sec-card-body { padding: 1.25rem 1.5rem; }

/* ── Section Divider Label ───────────────────────── */
.section-label {
    font-size: .6875rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: #a8aaae;
    display: flex; align-items: center; gap: .5rem;
    margin: 1.25rem 0 1rem;
}
.section-label:first-child { margin-top: 0; }
.section-label::after { content: ''; flex: 1; height: 1px; background: rgba(75,70,92,.07); }

/* ── Form Elements ───────────────────────────────── */
.form-label-mat {
    font-size: .75rem; font-weight: 600; color: #4b465c;
    margin-bottom: .375rem; display: block;
}
.form-label-mat .req { color: #ea5455; margin-left: 2px; }
.mat-input, .mat-textarea, .mat-select {
    width: 100%; background: #fafafa;
    border: 1px solid rgba(75,70,92,.12); border-radius: .375rem;
    color: #4b465c; font-family: 'Public Sans', sans-serif;
    font-size: .875rem; padding: .5rem .875rem;
    outline: none; transition: border-color .2s, box-shadow .2s;
}
.mat-input::placeholder, .mat-textarea::placeholder { color: #a8aaae; }
.mat-input:focus, .mat-textarea:focus, .mat-select:focus {
    border-color: #696cff; box-shadow: 0 0 0 3px rgba(105,108,255,.12);
}
.mat-input.is-invalid, .mat-textarea.is-invalid, .mat-select.is-invalid {
    border-color: #ea5455; box-shadow: 0 0 0 3px rgba(234,84,85,.1);
}
.mat-input:disabled { opacity: .55; cursor: not-allowed; }
.mat-textarea { resize: vertical; min-height: 100px; }
.mat-select   { appearance: none; cursor: pointer; }
.select-wrap  { position: relative; }
.select-wrap::after {
    content: '\ea4e'; font-family: 'remixicon';
    position: absolute; right: .75rem; top: 50%; transform: translateY(-50%);
    color: #a8aaae; font-size: .875rem; pointer-events: none;
}
.input-icon-wrap { position: relative; }
.input-icon-wrap .i-icon {
    position: absolute; left: .75rem; top: 50%; transform: translateY(-50%);
    color: #a8aaae; font-size: .9375rem; pointer-events: none;
}
.input-icon-wrap .mat-input { padding-left: 2.125rem; }
.field-hint  { font-size: .75rem; color: #a8aaae; margin-top: .3rem; }
.field-error { font-size: .75rem; color: #ea5455; margin-top: .3rem; }
.bio-counter { float: right; font-weight: 400; }

/* ── Avatar Upload Zone ──────────────────────────── */
.avatar-zone {
    width: 88px; height: 88px; border-radius: 50%;
    border: 2px dashed rgba(105,108,255,.35);
    background: rgba(105,108,255,.04);
    cursor: pointer; position: relative; overflow: hidden;
    transition: border-color .2s; flex-shrink: 0;
}
.avatar-zone:hover { border-color: #696cff; }
.avatar-zone img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
.avatar-zone-overlay {
    position: absolute; inset: 0; border-radius: 50%;
    background: rgba(105,108,255,.72);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 3px; opacity: 0; transition: opacity .2s;
    color: #fff; font-size: .6875rem; font-weight: 600;
}
.avatar-zone-overlay i { font-size: 1.125rem; }
.avatar-zone:hover .avatar-zone-overlay { opacity: 1; }

/* ── Checkbox Row ────────────────────────────────── */
.check-row {
    display: inline-flex; align-items: center; gap: .4rem;
    font-size: .8125rem; color: #6d6d6d; cursor: pointer; margin-top: .5rem;
}
.check-row input[type="checkbox"] {
    width: 15px; height: 15px; accent-color: #ea5455; cursor: pointer;
}

/* ── Readonly badge ──────────────────────────────── */
.badge-readonly {
    font-size: .65rem; background: rgba(75,70,92,.07);
    border: 1px solid rgba(75,70,92,.1); border-radius: .25rem;
    padding: 1px 6px; color: #a8aaae; margin-left: 4px;
    font-weight: 400; vertical-align: middle;
}

/* ── Meta Rows ───────────────────────────────────── */
.meta-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: .45rem 0; border-bottom: 1px solid rgba(75,70,92,.06);
}
.meta-row:first-child { padding-top: 0; }
.meta-row:last-child  { border-bottom: none; padding-bottom: 0; }
.meta-label { font-size: .75rem; color: #a8aaae; }
.meta-value { font-size: .8125rem; font-weight: 600; color: #4b465c; }
.meta-value.accent { color: #696cff; }

/* ── Submit Bar ──────────────────────────────────── */
.submit-bar {
    background: #fff; border: 1px solid rgba(75,70,92,.08);
    border-radius: .5rem; box-shadow: 0 2px 6px rgba(75,70,92,.06);
    padding: 1rem 1.5rem; display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap; gap: .75rem;
}
.submit-bar-info { font-size: .8125rem; color: #a8aaae; }
.btn-submit {
    display: inline-flex; align-items: center; gap: .5rem;
    font-family: 'Public Sans', sans-serif; font-size: .875rem; font-weight: 600;
    color: #fff; background: #696cff; border: none; border-radius: .375rem;
    padding: .5625rem 1.5rem; cursor: pointer; transition: all .2s;
    box-shadow: 0 4px 14px rgba(105,108,255,.4);
}
.btn-submit:hover    { background: #5f61e6; transform: translateY(-1px); }
.btn-submit:disabled { opacity: .65; cursor: not-allowed; transform: none; }
.btn-cancel {
    display: inline-flex; align-items: center; gap: .5rem;
    font-family: 'Public Sans', sans-serif; font-size: .875rem; font-weight: 500;
    color: #6d6d6d; background: #fff; border: 1px solid rgba(75,70,92,.2);
    border-radius: .375rem; padding: .5rem 1.25rem;
    text-decoration: none; transition: all .2s;
}
.btn-cancel:hover { border-color: #696cff; color: #696cff; background: rgba(105,108,255,.04); }

/* ── Animations ──────────────────────────────────── */
@keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
@keyframes spin   { from { transform:rotate(0); } to { transform:rotate(360deg); } }
.anim   { animation: fadeUp .3s ease both; }
.anim-1 { animation-delay: .05s; }
.anim-2 { animation-delay: .10s; }
.anim-3 { animation-delay: .15s; }
.anim-4 { animation-delay: .20s; }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y profile-page">

    {{-- ══ Page Header ══ --}}
    <div class="page-header-card anim">
        <div style="position:relative;z-index:1">
            <h4 class="page-title">
                <i class="ri ri-user-settings-line me-2"></i>My Profile
            </h4>
            <p class="page-breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <i class="ri ri-arrow-right-s-line mx-1"></i>
                Profile
            </p>
        </div>
    </div>

    <x-alert />

    {{-- ══ Hero ══ --}}
    <div class="hero-card anim anim-1">
        <div class="hero-avatar-wrap">
            <img src="{{asset('storage/' . $profile->avatarImage?->path)}}"
                 alt="{{ $profile->full_name }}"
                 class="hero-avatar" id="heroAvatar">
            <span class="hero-avatar-status"></span>
        </div>
        <div>
            <h5 class="hero-name">{{ $profile->full_name }}</h5>
            <p class="hero-role">Administrator</p>
            <div class="hero-chips">
                <span class="hero-chip">
                    <i class="ri ri-mail-line"></i>{{ $user->email }}
                </span>
                @if($profile->location)
                <span class="hero-chip">
                    <i class="ri ri-map-pin-line"></i>{{ $profile->location }}
                </span>
                @endif
                <span class="hero-chip">
                    <i class="ri ri-calendar-line"></i>Joined {{ $user->created_at->format('M Y') }}
                </span>
            </div>
        </div>
    </div>

    {{-- ══ Form ══ --}}
    <form action="{{ route('admin.profile.update') }}"
          method="POST" enctype="multipart/form-data" id="profileForm">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- ════ LEFT col (8) ════ --}}
            <div class="col-lg-8">

                {{-- ── Personal Information ── --}}
                <div class="sec-card anim anim-1">
                    <div class="sec-card-header">
                        <h6 class="sec-card-title">
                            <i class="ri ri-user-line"></i> Personal Information
                        </h6>
                    </div>
                    <div class="sec-card-body">

                        <div class="section-label" style="margin-top:0">
                            <i class="ri ri-image-line" style="color:#696cff"></i> Profile Photo
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="avatar-zone"
                                 onclick="document.getElementById('avatarInput').click()">
                                <img src="{{asset('storage/' . $profile->avatarImage?->path)}}" alt="avatar" id="avatarPreview">
                                <div class="avatar-zone-overlay">
                                    <i class="ri ri-camera-line"></i> Change
                                </div>
                            </div>
                            <div>
                                <div style="font-size:.8125rem;font-weight:600;color:#4b465c;margin-bottom:.2rem">
                                    Upload new photo
                                </div>
                                <div style="font-size:.75rem;color:#a8aaae;margin-bottom:.4rem">
                                    JPG, PNG or WebP — max 2 MB
                                </div>

                                @if($profile->avatarImage?->path)
                                <label class="check-row">
                                    <input type="checkbox" name="remove_avatar" value="1"
                                        id="removeAvatarCheck"
                                        onchange="handleRemoveCheck(this)">
                                        <span>Remove current photo</span>
                                </label>
                                @endif

                                @error('avatar')
                                    <p class="field-error mb-0">
                                        <i class="ri ri-error-warning-line me-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                        <input type="file" name="avatar" id="avatarInput"
                               accept="image/jpeg,image/png,image/webp"
                               style="display:none"
                               onchange="previewAvatar(this)">

                        <div class="section-label">
                            <i class="ri ri-profile-line" style="color:#696cff"></i> Basic Details
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label class="form-label-mat">First Name <span class="req">*</span></label>
                                <input type="text" name="first_name"
                                       class="mat-input @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name', $profile->first_name) }}" required>
                                @error('first_name')
                                    <p class="field-error">
                                        <i class="ri ri-error-warning-line me-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label-mat">Last Name <span class="req">*</span></label>
                                <input type="text" name="last_name"
                                       class="mat-input @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name', $profile->last_name) }}" required>
                                @error('last_name')
                                    <p class="field-error">
                                        <i class="ri ri-error-warning-line me-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label-mat">
                                Bio
                                <span class="field-hint bio-counter">
                                    <span id="bioCount">{{ strlen($profile->bio ?? '') }}</span> / 500
                                </span>
                            </label>
                            <textarea name="bio" rows="3" maxlength="500" id="bioText"
                                      class="mat-textarea @error('bio') is-invalid @enderror"
                                      oninput="document.getElementById('bioCount').textContent=this.value.length"
                                      placeholder="Write a short bio…">{{ old('bio', $profile->bio) }}</textarea>
                            @error('bio')
                                <p class="field-error">
                                    <i class="ri ri-error-warning-line me-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>
                </div>{{-- /sec-card Personal --}}

                {{-- ── Contact & Location ── --}}
                <div class="sec-card anim anim-2">
                    <div class="sec-card-header">
                        <h6 class="sec-card-title">
                            <i class="ri ri-map-pin-line"></i> Contact & Location
                        </h6>
                    </div>
                    <div class="sec-card-body">

                        <div class="section-label" style="margin-top:0">
                            <i class="ri ri-phone-line" style="color:#696cff"></i> Contact
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label class="form-label-mat">Phone Number</label>
                                <div class="input-icon-wrap">
                                    <i class="ri ri-phone-line i-icon"></i>
                                    <input type="tel" name="phone"
                                           class="mat-input @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $profile->phone) }}"
                                           placeholder="+20 100 000 0000">
                                </div>
                                @error('phone')
                                    <p class="field-error">
                                        <i class="ri ri-error-warning-line me-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label-mat">
                                    Email <span class="badge-readonly">read-only</span>
                                </label>
                                <div class="input-icon-wrap">
                                    <i class="ri ri-mail-line i-icon"></i>
                                    <input type="email" value="{{ $user->email }}"
                                           disabled class="mat-input">
                                </div>
                                <p class="field-hint">Managed from account settings.</p>
                            </div>
                        </div>

                        <div class="section-label">
                            <i class="ri ri-map-2-line" style="color:#696cff"></i> Location
                        </div>
                        <div class="mb-3">
                            <label class="form-label-mat">Address</label>
                            <div class="input-icon-wrap">
                                <i class="ri ri-home-3-line i-icon"></i>
                                <input type="text" name="address"
                                       class="mat-input @error('address') is-invalid @enderror"
                                       value="{{ old('address', $profile->address) }}"
                                       placeholder="Street, building…">
                            </div>
                            @error('address')
                                <p class="field-error">
                                    <i class="ri ri-error-warning-line me-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="row g-3 mb-0">
                            <div class="col-sm-6">
                                <label class="form-label-mat">City</label>
                                <input type="text" name="city"
                                       class="mat-input @error('city') is-invalid @enderror"
                                       value="{{ old('city', $profile->city) }}"
                                       placeholder="Cairo">
                                @error('city')
                                    <p class="field-error">
                                        <i class="ri ri-error-warning-line me-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                {{-- Select Country --}}
                                <label class="form-label-mat">Country</label>
                                <div class="select-wrap">
                                    <select name="country"
                                            class="mat-select @error('country') is-invalid @enderror">
                                        <option value="">— Select —</option>
                                        @foreach($countries as $code => $name)
                                            <option value="{{ $code }}"
                                                {{ old('country', $profile->country) === $code ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('country')
                                    <p class="field-error">
                                        <i class="ri ri-error-warning-line me-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>{{-- /sec-card Contact --}}

            </div>{{-- /col-lg-8 --}}

            {{-- ════ RIGHT col (4) ════ --}}
            <div class="col-lg-4">

                {{-- ── Additional Info ── --}}
                <div class="sec-card anim anim-2">
                    <div class="sec-card-header">
                        <h6 class="sec-card-title">
                            <i class="ri ri-id-card-line"></i> Additional Info
                        </h6>
                    </div>
                    <div class="sec-card-body">
                        <div class="mb-3">
                            <label class="form-label-mat">Date of Birth</label>
                            <div class="input-icon-wrap">
                                <i class="ri ri-cake-line i-icon"></i>
                                <input type="date" name="birth_date"
                                       class="mat-input @error('birth_date') is-invalid @enderror"
                                       value="{{ old('birth_date', $profile->birth_date?->format('Y-m-d')) }}"
                                       max="{{ now()->subDay()->format('Y-m-d') }}">
                            </div>
                            @error('birth_date')
                                <p class="field-error">
                                    <i class="ri ri-error-warning-line me-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label-mat">Gender</label>
                            <div class="select-wrap">
                                <select name="gender"
                                        class="mat-select @error('gender') is-invalid @enderror">
                                    <option value="">— Select —</option>
                                    <option value="male"
                                        {{ old('gender', $profile->gender) === 'male'   ? 'selected' : '' }}>
                                        Male
                                    </option>
                                    <option value="female"
                                        {{ old('gender', $profile->gender) === 'female' ? 'selected' : '' }}>
                                        Female
                                    </option>
                                </select>
                            </div>
                            @error('gender')
                                <p class="field-error">
                                    <i class="ri ri-error-warning-line me-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>{{-- /sec-card Additional --}}

                {{-- ── Account Info ── --}}
                <div class="sec-card anim anim-3">
                    <div class="sec-card-header">
                        <h6 class="sec-card-title">
                            <i class="ri ri-time-line"></i> Account Info
                        </h6>
                    </div>
                    <div class="sec-card-body" style="padding:.875rem 1.25rem">
                        <div class="meta-row">
                            <span class="meta-label">Member since</span>
                            <span class="meta-value">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Last updated</span>
                            <span class="meta-value">{{ $profile->updated_at->format('d M Y') }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">User ID</span>
                            <span class="meta-value accent">#{{ $user->id }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Role</span>
                            <span class="meta-value">Administrator</span>
                        </div>
                    </div>
                </div>{{-- /sec-card Account --}}

            </div>{{-- /col-lg-4 --}}

        </div>{{-- /row --}}

        {{-- ══ Submit Bar ══ --}}
        <div class="submit-bar mt-2 anim anim-4">
            <p class="submit-bar-info">
                <i class="ri ri-information-line me-1" style="color:#696cff"></i>
                Editing: <strong style="color:#4b465c">{{ $profile->full_name }}</strong>
            </p>
            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('dashboard') }}" class="btn-cancel">
                    <i class="ri ri-close-line"></i> Cancel
                </a>
                <button type="submit" class="btn-submit" id="saveBtn">
                    <i class="ri ri-save-line"></i> Save Changes
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
  function previewAvatar(input) {
    if (!input.files[0]) return;
    const url = URL.createObjectURL(input.files[0]);
    document.getElementById('avatarPreview').src = url;
    document.getElementById('heroAvatar').src    = url;
    // لو اختار صورة جديدة، شيل تيك remove تلقائياً
    const removeCheck = document.getElementById('removeAvatarCheck');
    if (removeCheck) removeCheck.checked = false;
  }

  function handleRemoveCheck(checkbox) {
    // لو عمل تيك على remove، امسح الـ file input
    if (checkbox.checked) {
      document.getElementById('avatarInput').value = '';
    }
  }

  document.getElementById('profileForm').addEventListener('submit', function () {
    const btn = document.getElementById('saveBtn');
    btn.disabled  = true;
    btn.innerHTML = '<i class="ri ri-loader-4-line" style="animation:spin .8s linear infinite"></i> Saving…';
  });
</script>
@endpush