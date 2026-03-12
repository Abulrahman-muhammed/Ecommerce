{{--
    Tags Section (Select2) — @include inside Organisation sec-card
    Required variables:
      $tags         → Collection of all Tag models
      $selectedTags → array of selected tag IDs (default [])
--}}
@php $selectedTags = $selectedTags ?? []; @endphp

<div class="mb-0 mt-3">
    <label class="form-label-mat" for="product-tags">Tags</label>

    <select name="tags[]"
            id="product-tags"
            class="mat-select-tags @error('tags') is-invalid @enderror"
            multiple>
        @foreach($tags as $tag)
            <option value="{{ $tag->id }}"
                {{ in_array($tag->id, (array) old('tags', $selectedTags)) ? 'selected' : '' }}>
                {{ $tag->name }}
            </option>
        @endforeach
    </select>

    @error('tags')
        <p class="field-error mt-1"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
    @enderror
    @error('tags.*')
        <p class="field-error mt-1"><i class="ri ri-error-warning-line me-1"></i>{{ $message }}</p>
    @enderror

    <p class="field-hint">Select one or more tags for this product</p>
</div>

@once
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<style>
/* ── Select2 → match mat-input style ── */
.select2-container { width: 100% !important; }

.select2-container--default .select2-selection--multiple {
    background: #fafafa;
    border: 1px solid rgba(75,70,92,.12) !important;
    border-radius: .375rem !important;
    min-height: 42px;
    padding: .25rem .5rem;
    font-family: 'Public Sans', sans-serif;
    transition: border-color .2s, box-shadow .2s;
    cursor: text;
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #696cff !important;
    box-shadow: 0 0 0 3px rgba(105,108,255,.12) !important;
    outline: none !important;
}

/* ── Purple chips ── */
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: #696cff !important;
    border: none !important;
    border-radius: .25rem !important;
    color: #fff !important;
    font-size: .75rem !important;
    font-weight: 600 !important;
    padding: 2px 8px !important;
    margin: 2px 3px 2px 0 !important;
    font-family: 'Public Sans', sans-serif;
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: rgba(255,255,255,.75) !important;
    font-size: .875rem !important;
    font-weight: 700 !important;
    margin-right: 4px !important;
    float: left;
    background: transparent !important;
    border: none !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #fff !important;
}

/* ── Type input ── */
.select2-container--default .select2-search--inline .select2-search__field {
    font-family: 'Public Sans', sans-serif;
    font-size: .875rem;
    color: #4b465c;
    margin-top: 4px;
}
.select2-container--default .select2-search--inline .select2-search__field::placeholder { color: #a8aaae; }

/* ── Dropdown panel ── */
.select2-dropdown {
    border: 1px solid rgba(75,70,92,.12) !important;
    border-radius: .375rem !important;
    box-shadow: 0 4px 18px rgba(75,70,92,.12) !important;
    font-family: 'Public Sans', sans-serif;
    overflow: hidden;
    z-index: 9999;
}
.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid rgba(75,70,92,.12) !important;
    border-radius: .25rem !important;
    font-family: 'Public Sans', sans-serif;
    font-size: .875rem;
    padding: .4375rem .75rem;
    color: #4b465c;
    outline: none;
}
.select2-container--default .select2-search--dropdown .select2-search__field:focus {
    border-color: #696cff !important;
    box-shadow: 0 0 0 3px rgba(105,108,255,.1);
}

/* ── Options ── */
.select2-results__option {
    font-size: .875rem;
    color: #4b465c;
    padding: .5rem .875rem;
    font-family: 'Public Sans', sans-serif;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background: rgba(105,108,255,.08) !important;
    color: #696cff !important;
}
.select2-container--default .select2-results__option[aria-selected="true"] {
    background: #696cff !important;
    color: #fff !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(function () {
    $('#product-tags').select2({
        placeholder:   'Search and select tags…',
        allowClear:    true,
        closeOnSelect: false,
        width:         '100%',
    });
});
</script>
@endpush
@endonce