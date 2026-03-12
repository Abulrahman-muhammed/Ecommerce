@extends('admin.layouts.master')

@section('title', 'Edit — ' . $product->name)

@push('styles')
<style>
.edit-page { font-family: 'Public Sans', sans-serif; }

/* ── Page Header ── */
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
.btn-header-danger {
    display: inline-flex; align-items: center; gap: .375rem;
    font-size: .8125rem; font-weight: 600; color: #fff;
    background: rgba(234,84,85,.8); border: none; border-radius: .375rem;
    padding: .4375rem 1rem; cursor: pointer; transition: all .2s;
}
.btn-header-danger:hover { background: #ea5455; color: #fff; transform: translateY(-1px); }

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
    display: flex; align-items: center; gap: .5rem; margin: 0; flex: 1;
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
.mat-textarea  { resize: vertical; min-height: 110px; }
.mat-select    { appearance: none; cursor: pointer; }
.select-wrap   { position: relative; }
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

/* ── Section Label ── */
.section-label {
    font-size: .6875rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: #a8aaae; margin: 0 0 1rem;
    display: flex; align-items: center; gap: .5rem;
}
.section-label::after { content: ''; flex: 1; height: 1px; background: rgba(75,70,92,.08); }

/* ── Current Main Image ── */
.current-main-wrap {
    position: relative; border-radius: .5rem; overflow: hidden;
    border: 1px solid rgba(75,70,92,.1); margin-bottom: .75rem;
}
.current-main-wrap img { width: 100%; max-height: 220px; object-fit: cover; display: block; }
.current-main-overlay {
    position: absolute; inset: 0; background: rgba(0,0,0,.45);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity .2s;
}
.current-main-wrap:hover .current-main-overlay { opacity: 1; }
.overlay-label { color: #fff; font-size: .8125rem; font-weight: 600; }
.remove-main-btn {
    position: absolute; top: .5rem; right: .5rem;
    background: rgba(234,84,85,.9); color: #fff; border: none;
    border-radius: .375rem; padding: 4px 10px;
    font-size: .75rem; font-weight: 600; cursor: pointer;
    display: flex; align-items: center; gap: .3rem; transition: background .15s;
}
.remove-main-btn:hover { background: #ea5455; }
.removed-badge {
    display: none; background: rgba(234,84,85,.08);
    border: 1px dashed rgba(234,84,85,.3);
    border-radius: .375rem; padding: .625rem 1rem;
    text-align: center; font-size: .8125rem; color: #ea5455;
}

/* ── Upload Zone ── */
.upload-zone {
    border: 2px dashed rgba(105,108,255,.25); border-radius: .5rem;
    background: rgba(105,108,255,.03); padding: 1.5rem;
    text-align: center; cursor: pointer; transition: all .2s;
}
.upload-zone:hover, .upload-zone.dragover {
    border-color: #696cff; background: rgba(105,108,255,.07);
}
.upload-icon-wrap {
    width: 48px; height: 48px; border-radius: .5rem;
    background: rgba(105,108,255,.1); color: #696cff;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .75rem; font-size: 1.375rem;
}
.upload-title { font-size: .875rem; font-weight: 600; color: #4b465c; margin: 0 0 .2rem; }
.upload-sub   { font-size: .75rem; color: #a8aaae; margin: 0; }

/* ── New Main Preview ── */
.new-main-preview {
    display: none; position: relative; border-radius: .5rem; overflow: hidden;
    border: 2px solid #696cff; margin-top: .625rem;
}
.new-main-preview img { width: 100%; max-height: 200px; object-fit: cover; display: block; }
.new-main-badge {
    position: absolute; top: .5rem; left: .5rem;
    background: #696cff; color: #fff; border-radius: .25rem;
    font-size: .65rem; font-weight: 700; padding: 2px 8px; letter-spacing: .05em;
}
.new-main-remove {
    position: absolute; top: .5rem; right: .5rem;
    background: rgba(0,0,0,.5); color: #fff; border: none;
    border-radius: .375rem; width: 28px; height: 28px;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: .875rem; transition: background .15s; backdrop-filter: blur(4px);
}
.new-main-remove:hover { background: rgba(234,84,85,.85); }

/* ── Gallery Count ── */
.gallery-count {
    display: inline-flex; align-items: center; gap: .3rem;
    background: rgba(105,108,255,.1); color: #696cff;
    border-radius: 50px; font-size: .6875rem; font-weight: 700;
    padding: 2px 10px; margin-left: .5rem;
}

/* ── Gallery Grid ── */
.gallery-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(88px, 1fr)); gap: .625rem;
}
.gallery-item {
    position: relative; aspect-ratio: 1;
    border-radius: .375rem; overflow: hidden;
    border: 2px solid transparent; transition: border-color .2s;
}
.gallery-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
.gallery-item.marked-remove { border-color: #ea5455; opacity: .55; }
.gallery-item.marked-remove::after {
    content: '\eb9b'; font-family: 'remixicon';
    position: absolute; inset: 0; display: flex;
    align-items: center; justify-content: center;
    background: rgba(234,84,85,.25); color: #ea5455; font-size: 1.5rem;
}
.gi-remove-btn {
    position: absolute; top: .25rem; right: .25rem;
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(234,84,85,.85); color: #fff; border: none;
    cursor: pointer; font-size: .65rem;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity .15s;
}
.gallery-item:hover .gi-remove-btn { opacity: 1; }

/* ── New Gallery Item ── */
.gallery-item-new {
    position: relative; aspect-ratio: 1;
    border-radius: .375rem; overflow: hidden;
    border: 2px solid #696cff;
    animation: galIn .22s ease both;
}
@keyframes galIn { from { opacity: 0; transform: scale(.88); } to { opacity: 1; transform: scale(1); } }
.gallery-item-new img { width: 100%; height: 100%; object-fit: cover; display: block; }
.gallery-item-new .new-badge {
    position: absolute; bottom: .25rem; left: .25rem;
    background: #696cff; color: #fff; border-radius: .2rem;
    font-size: .55rem; font-weight: 700; padding: 1px 5px;
}
.gin-remove {
    position: absolute; top: .25rem; right: .25rem;
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(234,84,85,.85); color: #fff; border: none;
    cursor: pointer; font-size: .65rem;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity .15s;
}
.gallery-item-new:hover .gin-remove { opacity: 1; }

/* ── Add Gallery Zone ── */
.gallery-add-zone {
    border: 2px dashed rgba(105,108,255,.2); border-radius: .5rem;
    background: rgba(105,108,255,.02); padding: 1.25rem 1rem;
    text-align: center; cursor: pointer; transition: all .2s; margin-bottom: .875rem;
}
.gallery-add-zone:hover, .gallery-add-zone.dragover {
    border-color: #696cff; background: rgba(105,108,255,.06);
}

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
.btn-submit:hover { background: #5f61e6; transform: translateY(-1px); }
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

/* SweetAlert overrides */
html.swal2-shown, body.swal2-shown { padding-right: 0 !important; overflow: auto !important; }
.swal2-container { z-index: 9999 !important; }
.swal2-popup { font-family: 'Public Sans', sans-serif !important; border-radius: .5rem !important; }
.swal2-title { font-size: 1.125rem !important; font-weight: 600 !important; color: #4b465c !important; }
.swal2-html-container { font-size: .875rem !important; color: #a8aaae !important; }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y edit-page">

    {{-- ── Header ── --}}
    <div class="page-header-card anim">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="page-title">
                    <i class="ri ri-edit-box-line me-2"></i>Edit Product
                </h4>
                <p class="page-breadcrumb">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    <a href="{{ route('admin.products.index') }}">Products</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    Edit
                </p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.products.show', $product->id) }}" class="btn-header-ghost">
                    <i class="ri ri-eye-line"></i> View
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn-header-ghost">
                    <i class="ri ri-arrow-left-line"></i> Back
                </a>
                <form action="{{ route('admin.products.destroy', $product->id) }}"
                      method="POST" class="delete-form m-0 p-0">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-header-danger delete-btn">
                        <i class="ri ri-delete-bin-line"></i> Trash
                    </button>
                </form>
            </div>
        </div>
    </div>

    <x-alert/>

    <form action="{{ route('admin.products.update', $product->id) }}"
          method="POST" enctype="multipart/form-data" id="edit-form">
        @csrf
        @method('PUT')

        {{-- Flag: remove existing main image --}}
        <input type="hidden" name="remove_main_image" id="remove-main-input" value="0">

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
                                   value="{{ old('name', $product->name) }}">
                            @error('name')
                                <p class="field-error"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label-mat">Slug</label>
                            <div class="input-icon-wrap">
                                <i class="ri ri-link-m i-icon"></i>
                                {{-- readonly: slug should not be changed on edit to avoid broken URLs --}}
                                <input type="text" name="slug" id="product-slug" readonly
                                       class="mat-input @error('slug') is-invalid @enderror"
                                       value="{{ old('slug', $product->slug) }}">
                            </div>
                            @error('slug')
                                <p class="field-error"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label-mat">Description</label>
                            <textarea name="description"
                                      class="mat-textarea @error('description') is-invalid @enderror"
                                      rows="5">{{ old('description', $product->description) }}</textarea>
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
                                           value="{{ old('price', $product->price) }}">
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
                                           value="{{ old('compare_price', $product->compare_price) }}">
                                </div>
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

                        @if($product->mainImage)
                        <div id="current-main-wrap" class="current-main-wrap">
                            <img src="{{ asset('storage/' . $product->mainImage->path) }}"
                                 alt="{{ $product->name }}">
                            <div class="current-main-overlay">
                                <span class="overlay-label"><i class="ri ri-image-line me-1"></i>Current Image</span>
                            </div>
                            <button type="button" class="remove-main-btn" id="remove-main-btn">
                                <i class="ri ri-delete-bin-line"></i> Remove
                            </button>
                        </div>
                        <div class="removed-badge" id="removed-badge">
                            <i class="ri ri-close-circle-line me-1"></i>
                            Image marked for removal — will be deleted on save
                            <button type="button" id="undo-remove-btn"
                                    style="background:none;border:none;color:#696cff;font-size:.75rem;font-weight:600;cursor:pointer;margin-left:.5rem">
                                Undo
                            </button>
                        </div>
                        @endif

                        <input type="file" name="main_image" id="main-image-input"
                               accept="image/jpeg,image/png,image/webp,image/gif" style="display:none">

                        <div class="upload-zone mt-2 {{ $product->mainImage ? 'd-none' : '' }}"
                             id="main-upload-zone">
                            <div class="upload-icon-wrap"><i class="ri ri-upload-cloud-2-line"></i></div>
                            <p class="upload-title">{{ $product->mainImage ? 'Replace image' : 'Upload main image' }}</p>
                            <p class="upload-sub">
                                <span style="color:#696cff;font-weight:600;cursor:pointer"
                                      id="main-browse-btn">Browse</span>
                                or drag &amp; drop — JPG, PNG, WebP
                            </p>
                        </div>

                        <div class="new-main-preview" id="new-main-preview">
                            <span class="new-main-badge">NEW</span>
                            <img id="new-main-img" src="" alt="new">
                            <button type="button" class="new-main-remove" id="new-main-remove">
                                <i class="ri ri-close-line"></i>
                            </button>
                        </div>

                        @error('main_image')
                            <p class="field-error mt-2"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                        @enderror

                        <hr style="border-color:rgba(75,70,92,.07);margin:1.5rem 0">

                        {{-- Gallery --}}
                        <div class="section-label">
                            <i class="ri ri-gallery-line" style="color:#696cff"></i>
                            Gallery Images
                            <span class="gallery-count" id="gallery-count">
                                {{ $product->gallery->count() }}
                            </span>
                        </div>

                        @if($product->gallery->count())
                        <div class="gallery-grid mb-3" id="existing-gallery-grid">
                            @foreach($product->gallery as $img)
                            <div class="gallery-item" id="gi-{{ $img->id }}" data-id="{{ $img->id }}">
                                <img src="{{ asset('storage/' . $img->path) }}" alt="gallery">
                                <button type="button" class="gi-remove-btn" data-id="{{ $img->id }}">
                                    <i class="ri ri-close-line"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <input type="file" name="gallery_images[]" id="gallery-input"
                               accept="image/jpeg,image/png,image/webp,image/gif"
                               multiple style="display:none">

                        <div class="gallery-add-zone" id="gallery-add-zone">
                            <i class="ri ri-add-circle-line" style="font-size:1.5rem;color:#696cff;opacity:.7"></i>
                            <p style="font-size:.8125rem;font-weight:600;color:#4b465c;margin:.3rem 0 .15rem">
                                Add more images
                            </p>
                            <p style="font-size:.75rem;color:#a8aaae;margin:0">
                                <span style="color:#696cff;font-weight:600;cursor:pointer"
                                      id="gallery-browse-btn">Browse</span>
                                or drag &amp; drop
                            </p>
                        </div>

                        <div class="gallery-grid mt-2" id="new-gallery-grid"></div>

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
                                    @foreach(App\Enums\ProductStatusEnum::cases() as $s)
                                        <option value="{{ $s->value }}"
                                            {{ old('status', $product->status->value) == $s->value ? 'selected' : '' }}>
                                            {{ $s->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('status')
                                <p class="field-error"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label-mat">Featured Product</label>
                            <div class="mat-switch-wrap">
                                <div>
                                    <div class="mat-switch-label">Mark as Featured</div>
                                    <div class="mat-switch-sub">Show on featured sections</div>
                                </div>
                                <label class="mat-switch">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" name="is_featured" value="1"
                                           {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
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
                                            {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('category_id')
                                <p class="field-error"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label-mat">Stock Quantity <span class="req">*</span></label>
                            <div class="input-icon-wrap">
                                <i class="ri ri-archive-stack-line i-icon"></i>
                                <input type="number" name="quantity" min="0"
                                       class="mat-input @error('quantity') is-invalid @enderror"
                                       value="{{ old('quantity', $product->quantity) }}">
                            </div>
                            @error('quantity')
                                <p class="field-error"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Meta --}}
                <div class="sec-card anim anim-4">
                    <div class="sec-card-header">
                        <h6 class="sec-card-title"><i class="ri ri-time-line"></i> Meta</h6>
                    </div>
                    <div class="sec-card-body" style="padding:.875rem 1.25rem">
                        <div style="display:flex;flex-direction:column;gap:.5rem">
                            @foreach([
                                ['Created', $product->created_at->format('d M Y')],
                                ['Updated', $product->updated_at->format('d M Y')],
                            ] as [$label, $value])
                            <div style="display:flex;align-items:center;justify-content:space-between">
                                <span style="font-size:.75rem;color:#a8aaae">{{ $label }}</span>
                                <span style="font-size:.8125rem;font-weight:600;color:#4b465c">{{ $value }}</span>
                            </div>
                            @endforeach
                            <div style="display:flex;align-items:center;justify-content:space-between">
                                <span style="font-size:.75rem;color:#a8aaae">ID</span>
                                <span style="font-size:.8125rem;font-weight:600;color:#696cff">#{{ $product->id }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Submit Bar --}}
        <div class="submit-bar mt-2 anim anim-4">
            <p class="submit-bar-info">
                <i class="ri ri-information-line me-1" style="color:#696cff"></i>
                Editing: <strong style="color:#4b465c">{{ $product->name }}</strong>
            </p>
            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('admin.products.index') }}" class="btn-cancel">
                    <i class="ri ri-close-line"></i> Cancel
                </a>
                <button type="submit" class="btn-submit" id="submit-btn">
                    <i class="ri ri-save-line"></i> Save Changes
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/* ── Slug (read-only on edit to avoid breaking URLs) ── */
const nameInput = document.getElementById('product-name');
const slugInput = document.getElementById('product-slug');

/* ── Remove Existing Main Image ── */
const removeMainBtn   = document.getElementById('remove-main-btn');
const undoRemoveBtn   = document.getElementById('undo-remove-btn');
const currentMainWrap = document.getElementById('current-main-wrap');
const removedBadge    = document.getElementById('removed-badge');
const removeMainInput = document.getElementById('remove-main-input');
const mainUploadZone  = document.getElementById('main-upload-zone');

removeMainBtn?.addEventListener('click', () => {
    removeMainInput.value = '1';
    currentMainWrap.style.display = 'none';
    removedBadge.style.display    = 'block';
    mainUploadZone?.classList.remove('d-none');
});

undoRemoveBtn?.addEventListener('click', () => {
    removeMainInput.value = '0';
    currentMainWrap.style.display = 'block';
    removedBadge.style.display    = 'none';
    mainUploadZone?.classList.add('d-none');
    clearNewMain();
});

/* ── New Main Image Upload ── */
const mainImageInput = document.getElementById('main-image-input');
const mainBrowseBtn  = document.getElementById('main-browse-btn');
const newMainPreview = document.getElementById('new-main-preview');
const newMainImg     = document.getElementById('new-main-img');
const newMainRemove  = document.getElementById('new-main-remove');

mainBrowseBtn?.addEventListener('click', () => mainImageInput.click());
mainUploadZone?.addEventListener('click', e => {
    // Avoid double-trigger when clicking browse button inside the zone
    if (e.target !== mainBrowseBtn) mainImageInput.click();
});

mainUploadZone?.addEventListener('dragover',  e => { e.preventDefault(); mainUploadZone.classList.add('dragover'); });
mainUploadZone?.addEventListener('dragleave', () => mainUploadZone.classList.remove('dragover'));
mainUploadZone?.addEventListener('drop', e => {
    e.preventDefault();
    mainUploadZone.classList.remove('dragover');
    const f = e.dataTransfer.files[0];
    if (f?.type.startsWith('image/')) setNewMain(f);
});
mainImageInput?.addEventListener('change', () => {
    if (mainImageInput.files[0]) setNewMain(mainImageInput.files[0]);
});

function setNewMain(file) {
    newMainImg.src = URL.createObjectURL(file);
    newMainPreview.style.display = 'block';
    if (mainUploadZone) mainUploadZone.style.display = 'none';
}
function clearNewMain() {
    newMainImg.src = '';
    newMainPreview.style.display = 'none';
    if (mainUploadZone) mainUploadZone.style.display = 'block';
    if (mainImageInput) mainImageInput.value = '';
}
newMainRemove?.addEventListener('click', clearNewMain);

/* ── Remove Existing Gallery Images ── */
let removedIds = [];

function updateGalleryCount() {
    const existing = document.querySelectorAll('.gallery-item:not(.marked-remove)').length;
    document.getElementById('gallery-count').textContent = existing + newGalleryFiles.length;
}

document.querySelectorAll('.gi-remove-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id   = parseInt(this.dataset.id);
        const item = document.getElementById('gi-' + id);

        if (item.classList.contains('marked-remove')) {
            // Undo removal
            item.classList.remove('marked-remove');
            removedIds = removedIds.filter(i => i !== id);
            document.querySelector(`input[name="remove_gallery_ids[]"][value="${id}"]`)?.remove();
        } else {
            // Mark for removal
            item.classList.add('marked-remove');
            removedIds.push(id);
            const hidden   = document.createElement('input');
            hidden.type    = 'hidden';
            hidden.name    = 'remove_gallery_ids[]';
            hidden.value   = id;
            document.getElementById('edit-form').appendChild(hidden);
        }
        updateGalleryCount();
    });
});

/* ── Add New Gallery Images ──
   newGalleryFiles[] holds File objects in memory.
   They are appended to FormData manually on submit.
   ── */
const galleryInput   = document.getElementById('gallery-input');
const galleryAddZone = document.getElementById('gallery-add-zone');
const galleryBrowse  = document.getElementById('gallery-browse-btn');
const newGalleryGrid = document.getElementById('new-gallery-grid');
const MAX_GALLERY    = 8;
let newGalleryFiles  = [];

galleryBrowse?.addEventListener('click', e => { e.stopPropagation(); galleryInput.click(); });
galleryAddZone?.addEventListener('click', () => galleryInput.click());
galleryAddZone?.addEventListener('dragover',  e => { e.preventDefault(); galleryAddZone.classList.add('dragover'); });
galleryAddZone?.addEventListener('dragleave', () => galleryAddZone.classList.remove('dragover'));
galleryAddZone?.addEventListener('drop', e => {
    e.preventDefault();
    galleryAddZone.classList.remove('dragover');
    addGalleryFiles(Array.from(e.dataTransfer.files));
});
galleryInput?.addEventListener('change', () => {
    addGalleryFiles(Array.from(galleryInput.files));
    galleryInput.value = ''; // reset so same file can be re-selected
});

function addGalleryFiles(files) {
    for (const file of files) {
        if (!file.type.startsWith('image/')) continue;
        if (newGalleryFiles.length >= MAX_GALLERY) break;
        newGalleryFiles.push(file);

        const idx  = newGalleryFiles.length - 1;
        const item = document.createElement('div');
        item.className     = 'gallery-item-new';
        item.dataset.index = idx;
        item.innerHTML = `
            <img src="${URL.createObjectURL(file)}" alt="">
            <span class="new-badge">NEW</span>
            <button type="button" class="gin-remove"><i class="ri ri-close-line"></i></button>
        `;
        item.querySelector('.gin-remove').addEventListener('click', () => {
            newGalleryFiles.splice(parseInt(item.dataset.index), 1);
            item.remove();
            // Re-index remaining new items
            document.querySelectorAll('.gallery-item-new').forEach((el, i) => {
                el.dataset.index = i;
            });
            updateGalleryCount();
        });
        newGalleryGrid.appendChild(item);
    }
    updateGalleryCount();
}

/* ── Delete Product Confirmation ── */
document.querySelector('.delete-btn')?.addEventListener('click', function () {
    const form = this.closest('.delete-form');
    Swal.fire({
        title: 'Move to Trash?',
        html: `<p style="color:#a8aaae;font-size:.875rem;margin:0">
                   This product will be moved to the
                   <strong style="color:#696cff">Trash</strong>.
                   You can restore it anytime.
               </p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="ri ri-delete-bin-line me-1"></i> Yes, Trash it',
        cancelButtonText: 'Cancel',
        scrollbarPadding: false,
        didOpen: () => { document.body.style.paddingRight = '0px'; },
        customClass: {
            confirmButton: 'btn btn-danger me-2 waves-effect',
            cancelButton:  'btn btn-label-secondary waves-effect',
        },
        buttonsStyling: false,
    }).then(r => { if (r.isConfirmed) form.submit(); });
});

/* ── Form Submit ──
   Same strategy as create: assign in-memory gallery files
   to a real <input type="file"> via DataTransfer, then let
   the browser submit natively so the redirect + flash
   session message work without any extra JS trickery.
   ── */
document.getElementById('edit-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const btn    = document.getElementById('submit-btn');
    const formEl = this;

    btn.disabled  = true;
    btn.innerHTML = '<i class="ri ri-loader-4-line" style="animation:spin .8s linear infinite"></i> Saving…';

    // If no new gallery files to inject, submit normally
    if (newGalleryFiles.length === 0) {
        formEl.submit();
        return;
    }

    // Pack all new gallery files into a DataTransfer FileList
    const dt = new DataTransfer();
    newGalleryFiles.forEach(file => dt.items.add(file));

    // Remove any stale gallery input then inject a fresh one
    const existing = formEl.querySelector('input[name="gallery_images[]"]');
    if (existing) existing.remove();

    const fileInput         = document.createElement('input');
    fileInput.type          = 'file';
    fileInput.name          = 'gallery_images[]';
    fileInput.multiple      = true;
    fileInput.style.display = 'none';
    fileInput.files         = dt.files;

    formEl.appendChild(fileInput);
    formEl.submit();
});
</script>
@endpush