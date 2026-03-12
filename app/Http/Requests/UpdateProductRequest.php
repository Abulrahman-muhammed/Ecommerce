<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        $productId = $this->route('product'); // route model binding أو id

        return [
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'compare_price'  => 'nullable|numeric|min:0|gt:price',
            'quantity'       => 'required|integer|min:0',
            'status'         => 'required|integer|in:' . implode(',', array_map(fn($s) => $s->value, \App\Enums\ProductStatusEnum::cases())),
            'is_featured'    => 'boolean',
            'category_id'    => 'nullable|exists:categories,id',

            // images
            'main_image'           => 'nullable|image|mimes:jpeg,png,webp,gif|max:2048',
            'remove_main_image'    => 'nullable|boolean',
            'gallery_images'       => 'nullable|array|max:8',
            'gallery_images.*'     => 'image|mimes:jpeg,png,webp,gif|max:2048',
            'remove_gallery_ids'   => 'nullable|array',
            'remove_gallery_ids.*' => 'integer|exists:images,id',
        ];
    }
}
