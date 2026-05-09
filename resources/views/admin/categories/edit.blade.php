@extends('admin.layouts.master')

@section('title', 'Edit Category')

@push('styles')
<style>
    :root {
        --accent:       #7367f0;
        --accent-soft:  rgba(115, 103, 240, 0.12);
        --accent-hover: #6254e8;
        --danger:       #ea5455;
        --text-primary: #4b465c;
        --text-muted:   #a5a3ae;
        --border:       #dbdade;
        --bg-body:      #f8f7fa;
        --card-bg:      #ffffff;
        --radius:       0.5rem;
        --shadow:       0 0.25rem 1.125rem rgba(161, 172, 184, 0.42);
    }

    /* ── Page header ── */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    .page-header h4 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--text-primary);
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 0.4rem 0.9rem;
        text-decoration: none;
        transition: border-color 0.18s, color 0.18s, box-shadow 0.18s;
    }
    .btn-back:hover {
        border-color: var(--accent);
        color: var(--accent);
        box-shadow: 0 2px 8px var(--accent-soft);
    }

    /* ── Card ── */
    .form-card {
        background: var(--card-bg);
        border: none;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    .form-card .card-header {
        background: var(--bg-body);
        border-bottom: 1px solid var(--border);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .form-card .card-header .header-icon {
        width: 32px;
        height: 32px;
        border-radius: 0.375rem;
        background: var(--accent-soft);
        color: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    .form-card .card-header span {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary);
    }
    .form-card .card-body {
        padding: 1.5rem;
    }

    /* ── Field wrapper ── */
    .field-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    /* ── Label ── */
    .f-label {
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--text-primary);
        margin: 0;
    }
    .f-label .required {
        color: var(--danger);
        margin-left: 2px;
    }

    /* ── Input / Select / Textarea ── */
    .f-control {
        width: 100%;
        padding: 0.5rem 0.875rem;
        font-size: 0.875rem;
        color: var(--text-primary);
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        transition: border-color 0.18s, box-shadow 0.18s;
        appearance: none;
        -webkit-appearance: none;
    }
    .f-control::placeholder { color: var(--text-muted); }
    .f-control:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 0.2rem var(--accent-soft);
    }
    .f-control.is-invalid { border-color: var(--danger); }
    .f-control.is-invalid:focus {
        box-shadow: 0 0 0 0.2rem rgba(234, 84, 85, 0.12);
    }
    select.f-control {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%23a5a3ae' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.875rem center;
        padding-right: 2.25rem;
        cursor: pointer;
    }

    /* ── File input ── */
    .file-wrapper input[type="file"] {
        width: 100%;
        padding: 0.45rem 0.875rem;
        font-size: 0.875rem;
        color: var(--text-primary);
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        cursor: pointer;
        transition: border-color 0.18s, box-shadow 0.18s;
    }
    .file-wrapper input[type="file"]:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 0.2rem var(--accent-soft);
    }
    .file-wrapper input[type="file"]::file-selector-button {
        background: var(--accent-soft);
        color: var(--accent);
        border: none;
        border-radius: calc(var(--radius) - 2px);
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        margin-right: 0.6rem;
        transition: background 0.18s;
    }
    .file-wrapper input[type="file"]::file-selector-button:hover {
        background: rgba(115, 103, 240, 0.2);
    }

    /* ── Current image preview ── */
    .image-preview {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-top: 0.625rem;
        padding: 0.625rem 0.75rem;
        background: var(--bg-body);
        border: 1px dashed var(--border);
        border-radius: var(--radius);
    }
    .image-preview img {
        width: 52px;
        height: 52px;
        object-fit: cover;
        border-radius: calc(var(--radius) - 2px);
        border: 1px solid var(--border);
    }
    .image-preview-meta {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }
    .image-preview-meta span {
        font-size: 0.78rem;
        font-weight: 500;
        color: var(--text-primary);
    }
    .image-preview-meta small {
        font-size: 0.73rem;
        color: var(--text-muted);
    }

    /* ── Status radio pills ── */
    .status-radio-group {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        padding-top: 0.25rem;
    }
    .status-radio-group .radio-pill {
        position: relative;
    }
    .status-radio-group .radio-pill input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .status-radio-group .radio-pill label {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.375rem 0.875rem;
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--text-muted);
        border: 1px solid var(--border);
        border-radius: 2rem;
        cursor: pointer;
        user-select: none;
        transition: border-color 0.18s, color 0.18s, background 0.18s, box-shadow 0.18s;
    }
    .status-radio-group .radio-pill label::before {
        content: '';
        display: inline-block;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--text-muted);
        transition: background 0.18s;
    }
    .status-radio-group .radio-pill input:checked + label {
        border-color: var(--accent);
        color: var(--accent);
        background: var(--accent-soft);
        box-shadow: 0 2px 6px var(--accent-soft);
    }
    .status-radio-group .radio-pill input:checked + label::before {
        background: var(--accent);
    }

    /* ── Helper / Error text ── */
    .f-hint  { font-size: 0.76rem; color: var(--text-muted); margin: 0; }
    .f-error { font-size: 0.76rem; color: var(--danger);     margin: 0; }

    /* ── Featured toggle ── */
    .toggle-wrap {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-top: 0.25rem;
    }
    .toggle-switch {
        position: relative;
        width: 44px;
        height: 24px;
        flex-shrink: 0;
    }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-track {
        position: absolute;
        inset: 0;
        background: var(--border);
        border-radius: 50px;
        cursor: pointer;
        transition: background 0.22s;
    }
    .toggle-track::after {
        content: '';
        position: absolute;
        left: 3px;
        top: 3px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 1px 4px rgba(0,0,0,.18);
        transition: transform 0.22s;
    }
    .toggle-switch input:checked + .toggle-track { background: var(--accent); }
    .toggle-switch input:checked + .toggle-track::after { transform: translateX(20px); }
    .toggle-meta { display: flex; flex-direction: column; gap: 1px; }
    .toggle-meta strong {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--text-primary);
    }
    .toggle-meta small { font-size: 0.74rem; color: var(--text-muted); }

    /* ── Divider ── */
    .form-divider {
        border: none;
        border-top: 1px solid var(--border);
        margin: 1.5rem 0 1.25rem;
    }

    /* ── Buttons ── */
    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #fff;
        background: var(--accent);
        border: none;
        border-radius: var(--radius);
        cursor: pointer;
        transition: background 0.18s, box-shadow 0.18s, transform 0.1s;
        box-shadow: 0 4px 12px rgba(115, 103, 240, 0.35);
    }
    .btn-submit:hover {
        background: var(--accent-hover);
        box-shadow: 0 6px 16px rgba(115, 103, 240, 0.45);
    }
    .btn-submit:active {
        transform: translateY(1px);
        box-shadow: 0 2px 8px rgba(115, 103, 240, 0.3);
    }
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-primary);
        background: transparent;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        text-decoration: none;
        transition: border-color 0.18s, color 0.18s;
    }
    .btn-cancel:hover {
        border-color: var(--text-primary);
        color: var(--text-primary);
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Page Header --}}
    <div class="page-header">
        <h4>Edit Category</h4>
        <a href="{{ route('admin.categories.index') }}" class="btn-back">
            <i class="ri ri-arrow-left-line"></i> Back to Categories
        </a>
    </div>

    {{-- Form Card --}}
    <div class="card form-card">

        <div class="card-header">
            <div class="header-icon"><i class="ri ri-folder-settings-line"></i></div>
            <span>Category Details</span>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- Name --}}
                    <div class="col-md-6">
                        <div class="field-group">
                            <x-form.input
                                label="Category Name"
                                inputName="name"
                                type="text"
                                placeholder="e.g. Electronics"
                                :value="$category->name"
                                class="f-control"
                            />
                            @error('name')
                                <p class="f-error">{{ $message }}</p>
                            @else
                                <p class="f-hint">Use a clear, descriptive name.</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Parent Category --}}
                    <div class="col-md-6">
                        <div class="field-group">
                            <x-form.select
                                label="Parent Category"
                                inputName="parent_id"
                                :options="$categories"
                                inputValue="{{ $category->parent_id }}"
                                class="f-control"
                            />
                            <p class="f-hint">Leave empty to keep as a top-level category.</p>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="f-label">Status <span class="required">*</span></label>
                            <div class="status-radio-group">
                                @foreach(\App\Enums\CategoryStatusEnum::cases() as $status)
                                    <div class="radio-pill">
                                        <input type="radio" name="status"
                                               id="s-{{ $status->value }}"
                                               value="{{ $status->value }}"
                                               {{ (old('status', $category->status->value) === $status->value) ? 'checked' : '' }}>
                                        <label for="s-{{ $status->value }}">{{ $status->label() }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Featured --}}
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="f-label">Featured</label>
                            <div class="toggle-wrap">
                                <label class="toggle-switch">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" name="is_featured" value="1"
                                           id="is_featured"
                                           {{ old('is_featured', $category->is_featured) ? 'checked' : '' }}>
                                    <span class="toggle-track"></span>
                                </label>
                                <div class="toggle-meta">
                                    <strong>Show as Featured</strong>
                                    <small>Featured categories appear in highlighted sections.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Image --}}
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="f-label">Category Image</label>
                            <div class="file-wrapper">
                                <input type="file" name="image" accept="image/*">
                            </div>
                            <p class="f-hint">Upload a new image to replace the current one.</p>

                            @if($category->image)
                                <div class="image-preview">
                                    <img src="{{ $category->image_url }}" alt="Current category image">
                                    <div class="image-preview-meta">
                                        <span>Current Image</span>
                                        <small>Leave blank to keep this image.</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <div class="field-group">
                            <x-form.input
                                label="Description"
                                inputName="description"
                                type="textarea"
                                placeholder="Optional: briefly describe this category…"
                                :value="$category->description"
                                class="f-control"
                                rows="3"
                            />
                        </div>
                    </div>

                </div>

                <hr class="form-divider">

                <div class="d-flex justify-content-end align-items-center gap-2">
                    <a href="{{ route('admin.categories.index') }}" class="btn-cancel">
                        Cancel
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="ri ri-save-line"></i> Update Category
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection