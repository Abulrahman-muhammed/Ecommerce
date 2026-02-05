<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\CategoryStatusEnum;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'status',
        'description',
        'parent_id',
    ];

    protected $casts = [
        'status' => CategoryStatusEnum::class,
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id')->withDefault();
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->withDefault();
    }

    public function products()
    {
        return $this->hasMany(Product::class)->withDefault();
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    // get image
    public function getImageAttribute()
    {
        return $this->images()->where('usage', 'category image')->first();
    }
    // get image url
    public function getImageUrlAttribute()
    {
        return $this->getImageAttribute() ? asset('storage/' . $this->getImageAttribute()->path) : null;
    }
}
