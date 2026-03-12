<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ProductStatusEnum;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use App\Models\Image;
use App\Enums\ProductImageUsage;
class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'status',
        'description',
        'price',
        'compare_price',
        'is_featured',
        'quantity',
        'rating',
        'category_id',
    ];


    protected $casts = [
        'price'         => 'decimal:2',
        'compare_price' => 'decimal:2',
        'rating'        => 'decimal:1',
        'is_featured'   => 'boolean',
        'status' => ProductStatusEnum::class,
    ];
        public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // filtter scope
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
        $statusEnum = ProductStatusEnum::from((int) $filters['status']);
        $query->where('status', $statusEnum);
    }

    // category
    if (!empty($filters['category_id'])) {
        $query->where('category_id', (int) $filters['category_id']);
    }

    // featured
    if (isset($filters['is_featured']) && $filters['is_featured'] !== '') {
        $query->where('is_featured', (bool) $filters['is_featured']);
    }

    return $query;
}
    // image relationship
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function gallery()
    {
        return $this->images()->where('usage', ProductImageUsage::PRODUCT_GALLARY->value);
    }

    public function mainImage()
    {
        return $this->morphOne(Image::class, 'imageable')
                    ->where('usage', ProductImageUsage::PRODUCT_MAIN_IMAGE->value);
    }       
    // global scope for active products only
    // protected static function booted()
    // {
    //     static::addGlobalScope('active', function ($query) {
    //         $query->where('status', ProductStatusEnum::ACTIVE);
    //     });
    // }   

}