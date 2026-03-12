@extends('admin.layouts.master')

@section('title', 'Add Product')

@push('styles')
<style>
.create-page { font-family: 'Public Sans', sans-serif; }

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
.header-actions { display: flex; gap: .625rem; align-items: center; flex-wrap: wrap; position: relative; z-index: 1; }
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
.sec-card-body { padding: 1.25rem 1.5rem; }

/* ── Form Elements ── */
.form-label-mat { font-size: .75rem; font-weight: 600; color: #4b465c; margin-bottom: .375rem; display: block; }
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
.mat-textarea { resize: vertical; min-height: 110px; }
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

/* ── Toggle Switch ── */
.mat-switch-wrap {
    display: flex; align-items: center; justify-content: space-between;
    background: #fafafa; border: 1px solid rgba(75,70,92,.1);
    border-radius: .375rem; padding: .625rem 1rem;
}
.mat-switch-label { font-size: .875rem; font-weight: 500; color: #4b465c; }
.mat-switch-sub   { font-size: .75rem; color: #a8aaae; margin-top: 1px; }
.mat-switch { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
.mat-switch input { opacity: 0; width: 0; height: 0; }
.mat-switch-slider {
    position: absolute; inset: 0; background: rgba(75,70,92,.15);
    border-radius: 50px; cursor: pointer; transition: background .2s;
}
.mat-switch-slider::before {
    content: ''; position: absolute; width: 18px; height: 18px; border-radius: 50%;
    background: #fff; left: 3px; top: 3px;
    transition: transform .2s; box-shadow: 0 1px 4px rgba(0,0,0,.2);
}
.mat-switch input:checked + .mat-switch-slider { background: #696cff; }
.mat-switch input:checked + .mat-switch-slider::before { transform: translateX(20px); }

/* ── Price ── */
.price-prefix {
    position: absolute; left: .875rem; top: 50%; transform: translateY(-50%);
    font-size: .875rem; font-weight: 700; color: #a8aaae; pointer-events: none;
}
.price-input { padding-left: 1.875rem !important; }

/* ── Main Image Upload ── */
.main-upload-zone {
    border: 2px dashed rgba(105,108,255,.25); border-radius: .5rem;
    background: rgba(105,108,255,.03); padding: 2rem 1.5rem;
    text-align: center; cursor: pointer; transition: all .2s; position: relative;
}
.main-upload-zone:hover, .main-upload-zone.dragover {
    border-color: #696cff; background: rgba(105,108,255,.07);
}
.main-upload-zone input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.upload-icon-wrap {
    width: 56px; height: 56px; border-radius: .5rem;
    background: rgba(105,108,255,.1);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .875rem; font-size: 1.5rem; color: #696cff;
}
.upload-title { font-size: .875rem; font-weight: 600; color: #4b465c; margin: 0 0 .25rem; }
.upload-sub   { font-size: .75rem; color: #a8aaae; margin: 0; }
.upload-types {
    display: inline-flex; align-items: center; gap: .375rem;
    background: rgba(75,70,92,.06); border-radius: 50px;
    font-size: .7rem; color: #a8aaae; padding: 3px 10px; margin-top: .625rem;
}
.main-preview-wrap {
    display: none; position: relative;
    border-radius: .5rem; overflow: hidden;
    border: 1px solid rgba(75,70,92,.1);
}
.main-preview-wrap img { width: 100%; max-height: 260px; object-fit: cover; display: block; }
.main-preview-actions { position: absolute; top: .5rem; right: .5rem; display: flex; gap: .375rem; }
.preview-action-btn {
    width: 30px; height: 30px; border-radius: .375rem;
    display: flex; align-items: center; justify-content: center;
    background: rgba(0,0,0,.5); color: #fff; border: none;
    cursor: pointer; font-size: .875rem; transition: background .15s; backdrop-filter: blur(4px);
}
.preview-action-btn:hover { background: rgba(0,0,0,.75); }
.preview-action-btn.danger:hover { background: rgba(234,84,85,.85); }

/* ── Gallery Upload ── */
.gallery-upload-zone {
    border: 2px dashed rgba(105,108,255,.2); border-radius: .5rem;
    background: rgba(105,108,255,.02); padding: 1.5rem;
    text-align: center; cursor: pointer; transition: all .2s;
    position: relative; margin-bottom: 1rem;
}
.gallery-upload-zone:hover, .gallery-upload-zone.dragover {
    border-color: #696cff; background: rgba(105,108,255,.06);
}
.gallery-upload-icon { font-size: 1.75rem; color: #696cff; opacity: .6; margin-bottom: .5rem; }
.gallery-upload-title { font-size: .8125rem; font-weight: 600; color: #4b465c; margin: 0 0 .2rem; }
.gallery-upload-sub   { font-size: .75rem; color: #a8aaae; margin: 0; }
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
    gap: .625rem;
}
.gallery-item {
    position: relative; aspect-ratio: 1;
    border-radius: .375rem; overflow: hidden;
    border: 1px solid rgba(75,70,92,.1);
    animation: galleryIn .25s ease both;
}
@keyframes galleryIn {
    from { opacity: 0; transform: scale(.9); }
    to   { opacity: 1; transform: scale(1); }
}
.gallery-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
.gallery-item-remove {
    position: absolute; top: .25rem; right: .25rem;
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(234,84,85,.9); color: #fff;
    border: none; cursor: pointer; font-size: .6875rem;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity .15s;
}
.gallery-item:hover .gallery-item-remove { opacity: 1; }
.gallery-count {
    display: inline-flex; align-items: center; gap: .3rem;
    background: rgba(105,108,255,.1); color: #696cff;
    border-radius: 50px; font-size: .6875rem; font-weight: 700;
    padding: 2px 10px; margin-left: .5rem;
}

/* ── Section Label ── */
.section-label {
    font-size: .6875rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: #a8aaae; margin: 0 0 1rem;
    display: flex; align-items: center; gap: .5rem;
}
.section-label::after { content: ''; flex: 1; height: 1px; background: rgba(75,70,92,.08); }

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
.btn-submit:disabled { opacity: .65; cursor: not-allowed; transform: none; }
.btn-cancel {
    display: inline-flex; align-items: center; gap: .5rem;
    font-family: 'Public Sans', sans-serif; font-size: .875rem; font-weight: 500;
    color: #6d6d6d; background: #fff; border: 1px solid rgba(75,70,92,.2);
    border-radius: .375rem; padding: .5rem 1.25rem;
    text-decoration: none; transition: all .2s;
}
.btn-cancel:hover { border-color: #696cff; color: #696cff; background: rgba(105,108,255,.04); }

/* ── Animations ── */
@keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.anim   { animation: fadeUp .3s ease both; }
.anim-1 { animation-delay: .05s; }
.anim-2 { animation-delay: .10s; }
.anim-3 { animation-delay: .15s; }
.anim-4 { animation-delay: .20s; }
@keyframes spin { from { transform: rotate(0); } to { transform: rotate(360deg); } }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y create-page">

    {{-- ── Page Header ── --}}
    <div class="page-header-card anim">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="page-title">
                    <i class="ri ri-add-box-line me-2"></i>Add New Product
                </h4>
                <p class="page-breadcrumb">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    <a href="{{ route('admin.products.index') }}">Products</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    Create
                </p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.products.index') }}" class="btn-header-ghost">
                    <i class="ri ri-arrow-left-line"></i> Back to Products
                </a>
            </div>
        </div>
    </div>

    <x-alert/>

    <form id="product-form"
          action="{{ route('admin.products.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="row g-4">

            {{-- ════ LEFT ════ --}}
            <div class="col-lg-8">

                {{-- Basic Info --}}
                <div class="sec-card anim anim-1">
                    <div class="sec-card-header">
                        <h6 class="sec-card-title"><i class="ri ri-information-line"></i> Basic Information</h6>
                    </div>
                    <div class="sec-card-body">

                        <div class="mb-3">
                            <label class="form-label-mat">Product Name <span class="req">*</span></label>
                            <input type="text" name="name" id="product-name"
                                   class="mat-input @error('name') is-invalid @enderror"
                                   placeholder="e.g. Nike Air Max 270"
                                   value="{{ old('name') }}" autocomplete="off">
                            @error('name')
                                <p class="field-error"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label-mat">Slug</label>
                            <div class="input-icon-wrap">
                                <i class="ri ri-link-m i-icon"></i>
                                <input type="text" name="slug" id="product-slug"
                                       class="mat-input @error('slug') is-invalid @enderror"
                                       placeholder="auto-generated-from-name"
                                       value="{{ old('slug') }}">
                            </div>
                            <p class="field-hint">Leave blank to auto-generate from name</p>
                            @error('slug')
                                <p class="field-error"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label-mat">Description</label>
                            <textarea name="description"
                                      class="mat-textarea @error('description') is-invalid @enderror"
                                      placeholder="Write a detailed product description…"
                                      rows="5">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="field-error"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Pricing --}}
                <div class="sec-card anim anim-2">
                    <div class="sec-card-header">
                        <h6 class="sec-card-title"><i class="ri ri-money-dollar-circle-line"></i> Pricing</h6>
                    </div>
                    <div class="sec-card-body">
                        <div class="row g-3">

                            <div class="col-sm-6">
                                <label class="form-label-mat">Sale Price <span class="req">*</span></label>
                                <div style="position:relative">
                                    <span class="price-prefix">$</span>
                                    <input type="number" name="price" step="0.01" min="0"
                                           class="mat-input price-input @error('price') is-invalid @enderror"
                                           placeholder="0.00"
                                           value="{{ old('price') }}">
                                </div>
                                @error('price')
                                    <p class="field-error"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label-mat">Compare-at Price</label>
                                <div style="position:relative">
                                    <span class="price-prefix">$</span>
                                    <input type="number" name="compare_price" step="0.01" min="0"
                                           class="mat-input price-input @error('compare_price') is-invalid @enderror"
                                           placeholder="0.00"
                                           value="{{ old('compare_price') }}">
                                </div>
                                <p class="field-hint">Original price shown as strikethrough</p>
                                @error('compare_price')
                                    <p class="field-error"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Images --}}
                <div class="sec-card anim anim-3">
                    <div class="sec-card-header">
                        <h6 class="sec-card-title"><i class="ri ri-image-2-line"></i> Product Images</h6>
                    </div>
                    <div class="sec-card-body">

                        {{-- Main Image --}}
                        <div class="section-label">
                            <i class="ri ri-star-line" style="color:#696cff"></i> Main Image
                        </div>

                        <div class="main-upload-zone" id="main-upload-zone">
                            <input type="file" name="main_image" id="main-image-input"
                                   accept="image/jpeg,image/png,image/webp,image/gif">
                            <div id="main-upload-placeholder">
                                <div class="upload-icon-wrap">
                                    <i class="ri ri-upload-cloud-2-line"></i>
                                </div>
                                <p class="upload-title">Drop your main image here</p>
                                <p class="upload-sub">or click to browse files</p>
                                <span class="upload-types">
                                    <i class="ri ri-image-line"></i>
                                    JPG, PNG, WebP — Max 2MB
                                </span>
                            </div>
                        </div>

                        <div class="main-preview-wrap mt-2" id="main-preview-wrap">
                            <img id="main-preview-img" src="" alt="preview">
                            <div class="main-preview-actions">
                                <button type="button" class="preview-action-btn danger"
                                        id="main-remove-btn" title="Remove">
                                    <i class="ri ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>

                        @error('main_image')
                            <p class="field-error mt-2"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                        @enderror

                        <hr style="border-color:rgba(75,70,92,.07);margin:1.5rem 0">

                        {{-- Gallery --}}
                        <div class="section-label">
                            <i class="ri ri-gallery-line" style="color:#696cff"></i>
                            Gallery Images
                            <span class="gallery-count" id="gallery-count">0</span>
                        </div>

                        {{-- Hidden file input — only used to trigger the picker --}}
                        <input type="file" name="gallery_images[]" id="gallery-input"
                               accept="image/jpeg,image/png,image/webp,image/gif"
                               multiple style="display:none">

                        <div class="gallery-upload-zone" id="gallery-upload-zone">
                            <div class="gallery-upload-icon">
                                <i class="ri ri-add-circle-line"></i>
                            </div>
                            <p class="gallery-upload-title">Add gallery images</p>
                            <p class="gallery-upload-sub">
                                <span style="color:#696cff;font-weight:600;text-decoration:underline;cursor:pointer"
                                      id="gallery-browse-btn">Browse files</span>
                                &nbsp;or drag &amp; drop here
                            </p>
                        </div>

                        <div class="gallery-grid" id="gallery-grid"></div>

                        @error('gallery_images')
                            <p class="field-error mt-2"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                        @enderror
                        @error('gallery_images.*')
                            <p class="field-error mt-1"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                        @enderror

                    </div>
                </div>

            </div>

            {{-- ════ RIGHT ════ --}}
            <div class="col-lg-4">

                {{-- Settings --}}
                <div class="sec-card anim anim-2">
                    <div class="sec-card-header">
                        <h6 class="sec-card-title"><i class="ri ri-settings-4-line"></i> Settings</h6>
                    </div>
                    <div class="sec-card-body">

                        <div class="mb-3">
                            <label class="form-label-mat">Status <span class="req">*</span></label>
                            <div class="select-wrap">
                                <select name="status" class="mat-select @error('status') is-invalid @enderror">
                                    @foreach(App\Enums\ProductStatusEnum::cases() as $status)
                                        <option value="{{ $status->value }}"
                                            {{ old('status', App\Enums\ProductStatusEnum::ACTIVE->value) == $status->value ? 'selected' : '' }}>
                                            {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('status')
                                <p class="field-error"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label-mat">Featured Product</label>
                            <div class="mat-switch-wrap">
                                <div>
                                    <div class="mat-switch-label">Mark as Featured</div>
                                    <div class="mat-switch-sub">Show on featured sections</div>
                                </div>
                                <label class="mat-switch">
                                    {{-- Hidden input ensures value=0 is sent when checkbox is unchecked --}}
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" name="is_featured" value="1"
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    <span class="mat-switch-slider"></span>
                                </label>
                            </div>
                        </div>
                        @include('admin.products._tags_section', ['tags' => $tags, 'selectedTags' => $selectedTags ?? []])
                    </div>
                </div>

                {{-- Organisation --}}
                <div class="sec-card anim anim-3">
                    <div class="sec-card-header">
                        <h6 class="sec-card-title"><i class="ri ri-price-tag-3-line"></i> Organisation</h6>
                    </div>
                    <div class="sec-card-body">

                        <div class="mb-3">
                            <label class="form-label-mat">Category</label>
                            <div class="select-wrap">
                                <select name="category_id" class="mat-select @error('category_id') is-invalid @enderror">
                                    <option value="">— No Category —</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('category_id')
                                <p class="field-error"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label-mat">Stock Quantity <span class="req">*</span></label>
                            <div class="input-icon-wrap">
                                <i class="ri ri-archive-stack-line i-icon"></i>
                                <input type="number" name="quantity" min="0"
                                       class="mat-input @error('quantity') is-invalid @enderror"
                                       placeholder="0"
                                       value="{{ old('quantity', 0) }}">
                            </div>
                            @error('quantity')
                                <p class="field-error"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Quick Tips --}}
                <div class="sec-card anim anim-4">
                    <div class="sec-card-header">
                        <h6 class="sec-card-title"><i class="ri ri-lightbulb-line"></i> Quick Tips</h6>
                    </div>
                    <div class="sec-card-body" style="padding:1rem 1.25rem">
                        @foreach([
                            ['ri-image-line',       'Use a square main image for best display results.'],
                            ['ri-gallery-line',     'Add up to 8 gallery images to showcase your product.'],
                            ['ri-price-tag-3-line', 'Set a compare-at price to show a discount badge.'],
                            ['ri-star-line',        'Featured products appear in homepage sections.'],
                        ] as [$icon, $tip])
                        <div style="display:flex;gap:.625rem;align-items:flex-start;padding:.4rem 0;border-bottom:1px solid rgba(75,70,92,.05)">
                            <i class="ri {{ $icon }}" style="color:#696cff;font-size:.9375rem;margin-top:1px;flex-shrink:0"></i>
                            <span style="font-size:.78rem;color:#a8aaae;line-height:1.5">{{ $tip }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        {{-- Submit Bar --}}
        <div class="submit-bar mt-2 anim anim-4">
            <p class="submit-bar-info">
                <i class="ri ri-information-line me-1" style="color:#696cff"></i>
                Fields marked with <strong style="color:#ea5455">*</strong> are required
            </p>
            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('admin.products.index') }}" class="btn-cancel">
                    <i class="ri ri-close-line"></i> Cancel
                </a>
                <button type="submit" class="btn-submit" id="submit-btn">
                    <i class="ri ri-save-line"></i> Save Product
                </button>
            </div>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
/* ── Auto Slug ── */
const nameInput = document.getElementById('product-name');
const slugInput = document.getElementById('product-slug');
let slugManual  = slugInput.value.length > 0;

nameInput.addEventListener('input', () => {
    if (slugManual) return;
    slugInput.value = nameInput.value
        .toLowerCase().trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
});
slugInput.addEventListener('input', () => {
    slugManual = slugInput.value.length > 0;
});

/* ── Main Image Preview ── */
const mainInput       = document.getElementById('main-image-input');
const mainZone        = document.getElementById('main-upload-zone');
const mainPreviewWrap = document.getElementById('main-preview-wrap');
const mainPreviewImg  = document.getElementById('main-preview-img');
const mainRemoveBtn   = document.getElementById('main-remove-btn');

function showMainPreview(file) {
    mainPreviewImg.src = URL.createObjectURL(file);
    mainPreviewWrap.style.display = 'block';
    mainZone.style.display = 'none';
}
function clearMainPreview() {
    mainPreviewImg.src = '';
    mainPreviewWrap.style.display = 'none';
    mainZone.style.display = 'block';
    mainInput.value = '';
}

mainInput.addEventListener('change', () => {
    if (mainInput.files[0]) showMainPreview(mainInput.files[0]);
});
mainRemoveBtn.addEventListener('click', clearMainPreview);

mainZone.addEventListener('dragover',  e => { e.preventDefault(); mainZone.classList.add('dragover'); });
mainZone.addEventListener('dragleave', () => mainZone.classList.remove('dragover'));
mainZone.addEventListener('drop', e => {
    e.preventDefault();
    mainZone.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        mainInput.files = dt.files;
        showMainPreview(file);
    }
});

/* ── Gallery Images ──
   galleryFiles[] keeps File objects in memory.
   On submit they are appended manually to FormData.
   ── */
const galleryInput = document.getElementById('gallery-input');
const galleryGrid  = document.getElementById('gallery-grid');
const galleryZone  = document.getElementById('gallery-upload-zone');
const galleryCount = document.getElementById('gallery-count');
const MAX_GALLERY  = 8;
let galleryFiles   = [];

function updateGalleryCount() {
    galleryCount.textContent = galleryFiles.length;
}

function renderGalleryItem(file, idx) {
    const item = document.createElement('div');
    item.className   = 'gallery-item';
    item.dataset.idx = idx;

    const img = document.createElement('img');
    img.src   = URL.createObjectURL(file);
    img.alt   = file.name;

    const btn      = document.createElement('button');
    btn.type       = 'button';
    btn.className  = 'gallery-item-remove';
    btn.innerHTML  = '<i class="ri ri-close-line"></i>';
    btn.addEventListener('click', () => removeGalleryItem(idx));

    item.appendChild(img);
    item.appendChild(btn);
    galleryGrid.appendChild(item);
}

function rebuildGrid() {
    galleryGrid.innerHTML = '';
    galleryFiles.forEach((file, idx) => renderGalleryItem(file, idx));
    updateGalleryCount();
}

function addGalleryFiles(files) {
    for (const file of files) {
        if (!file.type.startsWith('image/')) continue;
        if (galleryFiles.length >= MAX_GALLERY) break;
        galleryFiles.push(file);
    }
    rebuildGrid();
}

function removeGalleryItem(idx) {
    galleryFiles.splice(idx, 1);
    rebuildGrid();
}

galleryZone.addEventListener('click', () => galleryInput.click());
document.getElementById('gallery-browse-btn').addEventListener('click', e => {
    e.stopPropagation();
    galleryInput.click();
});
galleryInput.addEventListener('change', () => {
    if (galleryInput.files.length) addGalleryFiles(Array.from(galleryInput.files));
    galleryInput.value = ''; // reset so same file can be re-selected
});

galleryZone.addEventListener('dragover',  e => { e.preventDefault(); galleryZone.classList.add('dragover'); });
galleryZone.addEventListener('dragleave', () => galleryZone.classList.remove('dragover'));
galleryZone.addEventListener('drop', e => {
    e.preventDefault();
    galleryZone.classList.remove('dragover');
    addGalleryFiles(Array.from(e.dataTransfer.files));
});

/* ── Form Submit ──
   We can't use a plain native submit because File objects
   selected via our in-memory galleryFiles[] array are NOT
   attached to any real <input> in the DOM.

   Solution: convert every File in galleryFiles[] into a
   hidden <input type="file"> equivalent by building a new
   temporary <form>, injecting the files via DataTransfer,
   and submitting it natively so the browser handles the
   multipart POST and follows the redirect itself — meaning
   the flash session message will appear on the next page.
   ── */
document.getElementById('product-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const btn    = document.getElementById('submit-btn');
    const formEl = this;

    btn.disabled  = true;
    btn.innerHTML = '<i class="ri ri-loader-4-line" style="animation:spin .8s linear infinite"></i> Saving…';

    // If no in-memory gallery files, just submit normally
    if (galleryFiles.length === 0) {
        formEl.submit();
        return;
    }

    // Build a hidden file input carrying all gallery files via DataTransfer
    const dt = new DataTransfer();
    galleryFiles.forEach(file => dt.items.add(file));

    // Remove any existing gallery input to avoid duplicates
    const existing = formEl.querySelector('input[name="gallery_images[]"]');
    if (existing) existing.remove();

    const fileInput      = document.createElement('input');
    fileInput.type       = 'file';
    fileInput.name       = 'gallery_images[]';
    fileInput.multiple   = true;
    fileInput.style.display = 'none';
    fileInput.files      = dt.files;   // assign the DataTransfer FileList

    formEl.appendChild(fileInput);
    formEl.submit();
});
</script>
@endpush