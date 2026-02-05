<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\CategoryStatusEnum;

class StoreCategoryRequest extends FormRequest
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
        'name' => 'required|string|max:255|unique:categories,name',
        'description' => 'required|string',
        'status' => 'required|in:' . implode(',', CategoryStatusEnum::getValues()),
        'slug' => 'nullable|string|max:255|unique:categories,slug',
        'parent_id' => 'nullable|exists:categories,id|not_in:'.$this->route('category'),
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [            
            'slug.string' => 'Slug must be a string',
            'slug.max' => 'Slug must be less than 255 characters',
            'slug.unique' => 'Slug must be unique',         
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name must be less than 255 characters',
            'name.unique' => 'Name must be unique',
            'description.required' => 'Description is required',
            'description.string' => 'Description must be a string',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be ' . implode(',', CategoryStatusEnum::getValues()),
            'parent_id.nullable' => 'Parent Category must be nullable',
            'parent_id.exists' => 'Parent Category must exist in categories table',
            'parent_id.not_in' => 'Parent Category must not be the same as the category being edited',
            'image.nullable' => 'Image is nullable',
            'image.image' => 'Image must be an image',
            'image.mimes' => 'Image must be a jpeg, png, jpg, or gif',
            'image.max' => 'Image must be less than 2048 kilobytes',
        ];
    }

}
