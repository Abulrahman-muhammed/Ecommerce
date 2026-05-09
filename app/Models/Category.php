<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\CategoryStatusEnum;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Image;
class Category extends Model
{
    use SoftDeletes , HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'status',
        'description',
        'parent_id',
        'is_featured',
    ];

    protected $casts = [
        'status' => CategoryStatusEnum::class,
        'is_featured' => 'boolean',
    ];

public function scopeFilter($query, $filters)
{
    // search
    if (!empty($filters['search'])) {
        $query->where(function ($q) use ($filters) {
            $q->where('name', 'like', "%{$filters['search']}%")
              ->orWhere('slug', 'like', "%{$filters['search']}%");
        });
    }

    // status
    if (isset($filters['status']) && $filters['status'] !== '') {
        $statusEnum = CategoryStatusEnum::from((int) $filters['status']);
        $query->where('status', $statusEnum);
    }

    // is_featured
    // array_key_exists is used instead of isset() because isset() returns false
    // for keys that exist but hold falsy values like "0", null, or false.
    if (array_key_exists('is_featured', $filters) && $filters['is_featured'] !== '') {
        $query->where('is_featured', $filters['is_featured'] === '1');
    }

    return $query;
}

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
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

    public function scopeActive($query)
    {
        return $query->where('status', CategoryStatusEnum::ACTIVE);
    }
}
