<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\cartObserver;
class Cart extends Model
{
    public $incrementing = false;
    
    protected $fillable = [
        'cookie_id',
        'user_id',
        'product_id',
        'quantity',
    ];

    // Event (observer) creating , created , updating , updated , deleting , deleted , restoring , restored
    // to listen event static::creating() , static::created() , static::updating() , static::updated() , static::deleting() , static::deleted() , static::restoring() , static::restored()
    protected static function booted()
    {
        static::observe(cartObserver::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Guest User',
        ]);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
