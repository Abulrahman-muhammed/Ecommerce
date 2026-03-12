<label for="{{ $inputName }}" class="form-label">{{ $label }}</label>

<select 
    id="{{ $inputName }}" 
    name="{{ $inputName }}" 
    class="form-control select2 @error($inputName) is-invalid @enderror"
>
    <option value="">Select {{ $label }}</option>

    @foreach ($options as $option)
        <option value="{{ $option->id }}" 
            {{ old($inputName, $inputValue ?? '') == $option->id ? 'selected' : '' }}>
            {{ $option->name }}
        </option>
    @endforeach
</select>

@error($inputName)
    <span class="text-danger">{{ $message }}</span>
@enderror
