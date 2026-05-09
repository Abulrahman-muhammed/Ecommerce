<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    public function rules(): array
    {
        $adminId = Auth::guard('admin')->id();

        return [
            'name'         => ['required', 'string', 'max:255'],
            'username'     => ['required', 'string', 'max:255', Rule::unique('admins', 'username')->ignore($adminId)],
            'phone'        => ['nullable', 'string', 'max:20'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Name is required.',
            'username.unique'      => 'Username is already taken.',
            'email.unique'         => 'Email is already taken.',
            'phone.max'            => 'Phone number must not exceed 20 characters.',
            'image.max'            => 'Image must not exceed 2 MB.',
            'remove_image.boolean' => 'Remove image must be a boolean value.',
        ];
    }
}