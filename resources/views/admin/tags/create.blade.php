@extends('admin.layouts.master')

@section('title', 'Create Tag')

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
    font-size: .68rem; color: #696cff; padding: 1px 6px;
    font-weight: 600;
}

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
                    <i class="ri ri-price-tag-3-line me-2"></i>Create Tags
                </h4>
                <p class="page-breadcrumb">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    <a href="{{ route('admin.tags.index') }}">Tags</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    New Tags
                </p>
            </div>
            <div>
                <a href="{{ route('admin.tags.index') }}" class="btn-header-ghost">
                    <i class="ri ri-arrow-left-line"></i> Back to Tags
                </a>
            </div>
        </div>
    </div>

    <x-alert/>

    <form action="{{ route('admin.tags.store') }}" method="POST" id="tags-form">
        @csrf

        <div class="row g-4">

            {{-- ════ LEFT ════ --}}
            <div class="col-lg-8">
                <div class="sec-card anim anim-1">
                    <div class="sec-card-header">
                        <h6 class="sec-card-title">
                            <i class="ri ri-price-tag-3-line"></i> Add Tags
                        </h6>
                    </div>
                    <div class="sec-card-body">

                        <label class="form-label-mat">
                            Tag Names <span class="req">*</span>
                        </label>

                        {{-- Hidden inputs container — one per tag --}}
                        <div id="hidden-inputs"></div>

                        {{-- Tags Input Box --}}
                        <div class="tags-input-wrapper @error('names') is-invalid @enderror" id="tags-box" onclick="document.getElementById('tag-type-input').focus()">
                            {{-- Chips rendered here by JS --}}
                            <input
                                type="text"
                                id="tag-type-input"
                                class="tags-type-input"
                                placeholder="Type a tag name and press Enter or comma…"
                                autocomplete="off"
                                spellcheck="false"
                            >
                        </div>

                        @error('names')
                            <p class="field-error"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                        @enderror
                        @error('names.*')
                            <p class="field-error"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                        @enderror

                        <div class="tags-hint-strip">
                            <i class="ri ri-keyboard-line"></i>
                            Press <kbd>Enter</kbd> or <kbd>,</kbd> to add a tag &nbsp;·&nbsp;
                            Press <kbd>Backspace</kbd> to remove the last tag
                        </div>

                        <p class="field-hint" style="margin-top:.5rem">
                            <i class="ri ri-information-line me-1"></i>
                            Each tag's slug is auto-generated from its name on the server.
                        </p>

                    </div>
                </div>
            </div>

            {{-- ════ RIGHT ════ --}}
            <div class="col-lg-4">
                <div class="sec-card anim anim-2">
                    <div class="sec-card-header">
                        <h6 class="sec-card-title">
                            <i class="ri ri-lightbulb-line"></i> Quick Tips
                        </h6>
                    </div>
                    <div class="sec-card-body" style="padding:1rem 1.25rem">
                        @foreach([
                            ['ri-price-tag-3-line', 'Tag names should be short and descriptive.'],
                            ['ri-add-circle-line',  'You can add multiple tags at once by pressing Enter or comma.'],
                            ['ri-shopping-bag-line','Assign tags to products from the product edit page.'],
                            ['ri-search-line',      'Tags help customers filter and discover products.'],
                            ['ri-lock-line',        'Slugs are auto-generated from names on the server.'],
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
            <p class="submit-bar-info" id="submit-bar-count">
                <i class="ri ri-price-tag-3-line me-1" style="color:#696cff"></i>
                <span id="tag-count-text">No tags added yet</span>
            </p>
            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('admin.tags.index') }}" class="btn-cancel">
                    <i class="ri ri-close-line"></i> Cancel
                </a>
                <button type="submit" class="btn-submit" id="submit-btn">
                    <i class="ri ri-save-line"></i> Save Tags
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const box           = document.getElementById('tags-box');
    const typeInput     = document.getElementById('tag-type-input');
    const hiddenInputs  = document.getElementById('hidden-inputs');
    const tagCountText  = document.getElementById('tag-count-text');
    const submitBtn     = document.getElementById('submit-btn');

    let tags = [];

    /* ── Rebuild hidden <input name="names[]"> fields ── */
    function syncHidden() {
        hiddenInputs.innerHTML = '';
        tags.forEach(name => {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'names[]';
            inp.value = name;
            hiddenInputs.appendChild(inp);
        });

        // Counter text
        if (tags.length === 0) {
            tagCountText.textContent = 'No tags added yet';
        } else {
            tagCountText.textContent = tags.length + ' tag' + (tags.length > 1 ? 's' : '') + ' ready to save';
        }
    }

    /* ── Render all chips inside the box ── */
    function renderChips() {
        // Remove all existing chips (keep the type input)
        box.querySelectorAll('.tag-chip').forEach(c => c.remove());

        tags.forEach((name, idx) => {
            const chip = document.createElement('span');
            chip.className = 'tag-chip';
            chip.innerHTML = `${escHtml(name)}<button type="button" class="tag-chip-remove" data-idx="${idx}" title="Remove"><i class="ri ri-close-line"></i></button>`;
            box.insertBefore(chip, typeInput);
        });

        syncHidden();
    }

    /* ── Add a tag ── */
    function addTag(raw) {
        const name = raw.trim();
        if (!name) return;
        if (tags.map(t => t.toLowerCase()).includes(name.toLowerCase())) return; // dupe
        tags.push(name);
        renderChips();
    }

    /* ── Remove a tag by index ── */
    function removeTag(idx) {
        tags.splice(idx, 1);
        renderChips();
    }

    /* ── Keydown handler ── */
    typeInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            addTag(this.value);
            this.value = '';
        }
        if (e.key === 'Backspace' && this.value === '' && tags.length > 0) {
            removeTag(tags.length - 1);
        }
    });

    /* ── Also add on blur (if they tab away) ── */
    typeInput.addEventListener('blur', function () {
        if (this.value.trim()) {
            addTag(this.value);
            this.value = '';
        }
    });

    /* ── Paste: split by comma / newline ── */
    typeInput.addEventListener('paste', function (e) {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text');
        pasted.split(/[,\n]+/).forEach(part => addTag(part));
        this.value = '';
    });

    /* ── Remove chip click (delegation) ── */
    box.addEventListener('click', function (e) {
        const btn = e.target.closest('.tag-chip-remove');
        if (btn) removeTag(parseInt(btn.dataset.idx, 10));
    });

    /* ── HTML escape helper ── */
    function escHtml(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // Init empty
    syncHidden();
})();
</script>
@endpush