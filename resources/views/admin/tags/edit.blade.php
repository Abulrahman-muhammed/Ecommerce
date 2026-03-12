@extends('admin.layouts.master')

@section('title', 'Edit Tag')

@push('styles')
<style>
.tags-page { font-family: 'Public Sans', sans-serif; }

/* ── Page Header ── */
.page-header-card {
    background: linear-gradient(135deg, #696cff 0%, #9155fd 100%);
    border-radius: .5rem; padding: 1.5rem 1.75rem;
    margin-bottom: 1.5rem; position: relative;
    overflow: hidden; box-shadow: 0 4px 18px rgba(105,108,255,.35);
}
.page-header-card::before {
    content: ''; position: absolute; top: -40px; right: -30px;
    width: 160px; height: 160px; border-radius: 50%;
    background: rgba(255,255,255,.07);
}
.page-header-card::after {
    content: ''; position: absolute; bottom: -50px; right: 60px;
    width: 100px; height: 100px; border-radius: 50%;
    background: rgba(255,255,255,.05);
}
.page-title { font-size: 1.375rem; font-weight: 600; color: #fff; margin: 0 0 .25rem; }
.page-breadcrumb { font-size: .8125rem; color: rgba(255,255,255,.75); margin: 0; }
.page-breadcrumb a { color: rgba(255,255,255,.85); text-decoration: none; }
.page-breadcrumb a:hover { color: #fff; }
.tag-meta-chip {
    display: inline-flex; align-items: center; gap: .5rem;
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.2);
    border-radius: 50px; padding: .3rem .875rem;
    font-size: .78rem; color: rgba(255,255,255,.9); font-weight: 500;
    margin-top: .5rem; position: relative; z-index: 1;
}
.tag-meta-chip strong { color: #fff; font-weight: 700; }
.btn-header-ghost {
    display: inline-flex; align-items: center; gap: .375rem;
    font-size: .8125rem; font-weight: 500; color: rgba(255,255,255,.9);
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
    border-radius: .375rem; padding: .4375rem 1rem;
    text-decoration: none; transition: all .2s; backdrop-filter: blur(4px);
}
.btn-header-ghost:hover { background: rgba(255,255,255,.25); color: #fff; transform: translateY(-1px); }

/* ── Section Card ── */
.sec-card {
    background: #fff; border: 1px solid rgba(75,70,92,.08);
    border-radius: .5rem; box-shadow: 0 2px 6px rgba(75,70,92,.06);
    overflow: hidden; margin-bottom: 1.5rem;
}
.sec-card-header {
    display: flex; align-items: center; gap: .5rem;
    padding: .875rem 1.25rem; border-bottom: 1px solid rgba(75,70,92,.07);
}
.sec-card-title {
    font-size: .875rem; font-weight: 700; color: #4b465c;
    display: flex; align-items: center; gap: .5rem; margin: 0;
}
.sec-card-title i { color: #696cff; font-size: 1rem; }
.sec-card-body { padding: 1.5rem; }

/* ── Form Labels ── */
.form-label-mat { font-size: .75rem; font-weight: 600; color: #4b465c; margin-bottom: .375rem; display: block; }
.form-label-mat .req { color: #ea5455; margin-left: 2px; }
.field-hint  { font-size: .75rem; color: #a8aaae; margin-top: .375rem; }
.field-error { font-size: .75rem; color: #ea5455; margin-top: .375rem; }

/* ── Tags Input Box ── */
.tags-input-wrapper {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .375rem;
    min-height: 46px;
    padding: .4375rem .75rem;
    background: #fafafa;
    border: 1px solid rgba(75,70,92,.12);
    border-radius: .375rem;
    cursor: text;
    transition: border-color .2s, box-shadow .2s;
}
.tags-input-wrapper:focus-within {
    border-color: #696cff;
    box-shadow: 0 0 0 3px rgba(105,108,255,.12);
    background: #fff;
}
.tags-input-wrapper.is-invalid {
    border-color: #ea5455;
    box-shadow: 0 0 0 3px rgba(234,84,85,.1);
}

/* ── Single Tag Chip ── */
.tag-chip {
    display: inline-flex; align-items: center; gap: .3rem;
    background: #696cff; color: #fff;
    border-radius: .25rem;
    font-size: .78rem; font-weight: 600;
    padding: .2rem .55rem .2rem .65rem;
    white-space: nowrap;
    animation: chipIn .18s ease both;
}
@keyframes chipIn {
    from { opacity: 0; transform: scale(.85); }
    to   { opacity: 1; transform: scale(1); }
}
.tag-chip-remove {
    display: inline-flex; align-items: center; justify-content: center;
    width: 16px; height: 16px; border-radius: 50%;
    background: rgba(255,255,255,.25);
    border: none; cursor: pointer; color: #fff;
    font-size: .6rem; line-height: 1; padding: 0;
    transition: background .15s;
}
.tag-chip-remove:hover { background: rgba(255,255,255,.45); }

/* ── Typing Input inside box ── */
.tags-type-input {
    border: none; outline: none; background: transparent;
    font-family: 'Public Sans', sans-serif;
    font-size: .875rem; color: #4b465c;
    min-width: 120px; flex: 1;
    padding: .1rem .25rem;
}
.tags-type-input::placeholder { color: #a8aaae; }

/* ── Hint strip ── */
.tags-hint-strip {
    display: flex; align-items: center; gap: .375rem;
    margin-top: .5rem; font-size: .72rem; color: #a8aaae;
}
.tags-hint-strip kbd {
    display: inline-flex; align-items: center; justify-content: center;
    background: #f0eff5; border: 1px solid rgba(75,70,92,.15);
    border-radius: .2rem; font-family: 'Public Sans', sans-serif;
    font-size: .68rem; color: #696cff; padding: 1px 6px; font-weight: 600;
}

/* ── Info Banner ── */
.info-banner {
    display: flex; align-items: flex-start; gap: .75rem;
    background: rgba(105,108,255,.05); border: 1px solid rgba(105,108,255,.15);
    border-radius: .375rem; padding: .875rem 1rem;
    margin-bottom: 1.25rem; font-size: .8125rem; color: #696cff;
}
.info-banner i { flex-shrink: 0; margin-top: 1px; }

/* ── Stats ── */
.stat-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: .75rem 1.25rem; border-bottom: 1px solid rgba(75,70,92,.06);
}
.stat-item:last-child { border-bottom: none; }
.stat-label { font-size: .8125rem; color: #a8aaae; display: flex; align-items: center; gap: .375rem; }
.stat-value { font-size: .875rem; font-weight: 600; color: #4b465c; }

/* ── Tips ── */
.tip-row {
    display: flex; gap: .625rem; align-items: flex-start;
    padding: .45rem 0; border-bottom: 1px solid rgba(75,70,92,.05);
}
.tip-row:last-child { border-bottom: none; }

/* ── Submit Bar ── */
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
.btn-submit:hover { background: #5f61e6; box-shadow: 0 6px 20px rgba(105,108,255,.5); transform: translateY(-1px); }
.btn-cancel {
    display: inline-flex; align-items: center; gap: .5rem;
    font-family: 'Public Sans', sans-serif; font-size: .875rem; font-weight: 500;
    color: #6d6d6d; background: #fff; border: 1px solid rgba(75,70,92,.2);
    border-radius: .375rem; padding: .5rem 1.25rem;
    text-decoration: none; transition: all .2s;
}
.btn-cancel:hover { border-color: #696cff; color: #696cff; background: rgba(105,108,255,.04); }

/* ── Animations ── */
@keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
.anim   { animation: fadeUp .3s ease both; }
.anim-1 { animation-delay: .05s; }
.anim-2 { animation-delay: .10s; }
.anim-3 { animation-delay: .15s; }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y tags-page">

    {{-- ── Page Header ── --}}
    <div class="page-header-card anim">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="page-title">
                    <i class="ri ri-pencil-line me-2"></i>Edit Tag
                </h4>
                <p class="page-breadcrumb">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    <a href="{{ route('admin.tags.index') }}">Tags</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    Edit
                </p>
                <span class="tag-meta-chip">
                    <i class="ri ri-price-tag-3-line"></i>
                    <strong>{{ $tag->name }}</strong>
                </span>
            </div>
            <div>
                <a href="{{ route('admin.tags.index') }}" class="btn-header-ghost">
                    <i class="ri ri-arrow-left-line"></i> Back to Tags
                </a>
            </div>
        </div>
    </div>

    <x-alert/>

    {{-- Edit sends a single name, not an array --}}
    <form action="{{ route('admin.tags.update', $tag->id) }}" method="POST" id="tags-form">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- ════ LEFT ════ --}}
            <div class="col-lg-8">
                <div class="sec-card anim anim-1">
                    <div class="sec-card-header">
                        <h6 class="sec-card-title">
                            <i class="ri ri-pencil-line"></i> Edit Tag
                        </h6>
                    </div>
                    <div class="sec-card-body">

                        <div class="info-banner">
                            <i class="ri ri-information-line"></i>
                            <span>You can rename <strong>{{ $tag->name }}</strong> by removing the chip and typing a new name. The slug will be re-generated automatically.</span>
                        </div>

                        <label class="form-label-mat">
                            Tag Name <span class="req">*</span>
                        </label>

                        {{-- Hidden input — submitted to backend --}}
                        <input type="hidden" id="tag-hidden" name="name" value="{{ old('name', $tag->name) }}">

                        {{-- Tags Input Box — pre-filled with existing tag as a chip --}}
                        <div class="tags-input-wrapper @error('name') is-invalid @enderror"
                             id="tags-box"
                             onclick="document.getElementById('tag-type-input').focus()">
                            <input
                                type="text"
                                id="tag-type-input"
                                class="tags-type-input"
                                placeholder="Remove the chip and type a new name…"
                                autocomplete="off"
                                spellcheck="false"
                            >
                        </div>

                        @error('name')
                            <p class="field-error"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                        @enderror

                        <div class="tags-hint-strip">
                            <i class="ri ri-keyboard-line"></i>
                            Press <kbd>Enter</kbd> or <kbd>,</kbd> to confirm &nbsp;·&nbsp;
                            Press <kbd>Backspace</kbd> to remove
                        </div>

                        <p class="field-hint" style="margin-top:.5rem">
                            <i class="ri ri-lock-line me-1"></i>
                            Only one tag per edit. Slug is auto-generated on save.
                        </p>

                    </div>
                </div>
            </div>

            {{-- ════ RIGHT ════ --}}
            <div class="col-lg-4">

                {{-- Stats ── --}}
                <div class="sec-card anim anim-2">
                    <div class="sec-card-header">
                        <h6 class="sec-card-title">
                            <i class="ri ri-bar-chart-line"></i> Tag Details
                        </h6>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label"><i class="ri ri-hashtag"></i> ID</span>
                        <span class="stat-value">#{{ $tag->id }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label"><i class="ri ri-shopping-bag-line"></i> Products</span>
                        <span class="stat-value">{{ $tag->products()->count() }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label"><i class="ri ri-links-line"></i> Slug</span>
                        <span class="stat-value" style="font-family:'Courier New',monospace;font-size:.78rem;color:#696cff">{{ $tag->slug }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label"><i class="ri ri-calendar-line"></i> Created</span>
                        <span class="stat-value">{{ $tag->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label"><i class="ri ri-refresh-line"></i> Updated</span>
                        <span class="stat-value">{{ $tag->updated_at->diffForHumans() }}</span>
                    </div>
                </div>

                {{-- Tips ── --}}
                <div class="sec-card anim anim-2">
                    <div class="sec-card-header">
                        <h6 class="sec-card-title">
                            <i class="ri ri-lightbulb-line"></i> Quick Tips
                        </h6>
                    </div>
                    <div class="sec-card-body" style="padding:1rem 1.25rem">
                        @foreach([
                            ['ri-price-tag-3-line', 'Tag names should be short and descriptive.'],
                            ['ri-lock-line',        'Slug is re-generated automatically from the new name on save.'],
                            ['ri-shopping-bag-line','This tag is linked to ' . $tag->products()->count() . ' product(s).'],
                        ] as [$icon, $tip])
                        <div class="tip-row">
                            <i class="ri {{ $icon }}" style="color:#696cff;font-size:.9375rem;margin-top:1px;flex-shrink:0"></i>
                            <span style="font-size:.78rem;color:#a8aaae;line-height:1.5">{{ $tip }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Submit Bar ── --}}
        <div class="submit-bar anim anim-3">
            <p class="submit-bar-info" id="submit-bar-info">
                <i class="ri ri-price-tag-3-line me-1" style="color:#696cff"></i>
                <span id="bar-text">Editing: <strong>{{ old('name', $tag->name) }}</strong></span>
            </p>
            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('admin.tags.index') }}" class="btn-cancel">
                    <i class="ri ri-close-line"></i> Cancel
                </a>
                <button type="submit" class="btn-submit" id="submit-btn">
                    <i class="ri ri-save-line"></i> Update Tag
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const box        = document.getElementById('tags-box');
    const typeInput  = document.getElementById('tag-type-input');
    const hiddenInp  = document.getElementById('tag-hidden');
    const barText    = document.getElementById('bar-text');
    const submitBtn  = document.getElementById('submit-btn');

    // The tag currently set (only ONE allowed in edit mode)
    let currentTag = @json(old('name', $tag->name));

    /* ── Render the single chip ── */
    function renderChip() {
        box.querySelectorAll('.tag-chip').forEach(c => c.remove());

        if (currentTag) {
            const chip = document.createElement('span');
            chip.className = 'tag-chip';
            chip.innerHTML = `${escHtml(currentTag)}<button type="button" class="tag-chip-remove" title="Remove"><i class="ri ri-close-line"></i></button>`;
            chip.querySelector('.tag-chip-remove').addEventListener('click', removeChip);
            box.insertBefore(chip, typeInput);

            hiddenInp.value      = currentTag;
            typeInput.placeholder = 'Type a new name to replace…';
            barText.innerHTML    = 'Editing: <strong>' + escHtml(currentTag) + '</strong>';
            submitBtn.disabled   = false;
        } else {
            hiddenInp.value      = '';
            typeInput.placeholder = 'Type a tag name and press Enter…';
            barText.innerHTML    = '<span style="color:#ea5455">No tag name — please type one</span>';
            submitBtn.disabled   = true;
        }
    }

    function removeChip() {
        currentTag = '';
        renderChip();
        typeInput.focus();
    }

    function setTag(raw) {
        const name = raw.trim();
        if (!name) return;
        currentTag = name;
        renderChip();
        typeInput.value = '';
    }

    /* ── Keydown ── */
    typeInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            if (this.value.trim()) setTag(this.value);
        }
        if (e.key === 'Backspace' && this.value === '' && currentTag) {
            removeChip();
        }
    });

    /* ── Blur ── */
    typeInput.addEventListener('blur', function () {
        if (this.value.trim()) {
            setTag(this.value);
        }
    });

    /* ── HTML escape ── */
    function escHtml(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // Init — render existing tag as chip on page load
    renderChip();
})();
</script>
@endpush