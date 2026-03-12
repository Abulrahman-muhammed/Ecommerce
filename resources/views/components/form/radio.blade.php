<label for="{{ $name }}" class="form-label">
    {{ ucfirst($name) }} <span class="text-danger">*</span>
</label>

@foreach ($statuses as $status)
    <div class="form-check">
        <input 
            class="form-check-input @error($name) is-invalid @enderror" 
            type="radio" 
            name="{{ $name }}" 
            id="{{ $name }}-{{ $status->value }}" 
            value="{{ $status->value }}"
            {{ old($name, $oldValue) == $status->value ? 'checked' : '' }}
        >
        <label class="form-check-label" for="{{ $name }}-{{ $status->value }}">
            {{ ucfirst(strtolower($status->name)) }}
        </label>
    </div>
@endforeach

@error($name)
    <div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
