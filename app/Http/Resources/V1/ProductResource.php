<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\CategoryResource;
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'compare_price' => $this->compare_price,
            'rating' => $this->rating,
            'quantity' => $this->quantity,
            'is_featured' => $this->is_featured,
            'tags_count' => $this->tags_count,
            'main_image' => $this->main_image_url,
            'category' => new CategoryResource($this->whenLoaded('category')) ,
        ];
    }
}
