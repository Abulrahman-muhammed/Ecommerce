<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
 return [
            // ── Personal ──────────────────────────────
            'first_name'    => ['required', 'string', 'max:60'],
            'last_name'     => ['required', 'string', 'max:60'],
            'bio'           => ['nullable', 'string', 'max:500'],
            'birth_date'    => ['nullable', 'date', 'before:today'],
            'gender'        => ['nullable', 'in:male,female'],
            'avatar'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],

            // ── Contact ───────────────────────────────
            'phone'         => ['nullable', 'string', 'max:20'],

            // ── Location ──────────────────────────────
            'address'       => ['nullable', 'string', 'max:255'],
            'city'          => ['nullable', 'string', 'max:100'],
            'country'       => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required'  => 'Last name is required.',
            'birth_date.before'   => 'Birth date must be in the past.',
            'avatar.max'          => 'Avatar must not exceed 2 MB.',
        ];
    }
}
