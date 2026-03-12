<label for="{{ $inputName }}" class="form-label">{{ $label }}</label>

@if($type === 'textarea')
    <textarea 
        name="{{ $inputName }}" 
        id="{{ $inputName }}" 
        {{ $attributes->merge(['class' => 'form-control']) }}
    >{{ old($inputName, $value ?? '') }}</textarea>
@else
    <input 
        type="{{ $type }}" 
        id="{{ $inputName }}" 
        name="{{ $inputName }}" 
        value="{{ old($inputName, $value ?? '') }}" 
        {{ $attributes->merge(['class' => 'form-control']) }}
    />
@endif

@error($inputName)
    <span class="text-danger">{{ $message }}</span>
@enderror
