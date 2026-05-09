<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PaymentStatusEnum as PaymentStatus;
use Illuminate\Database\Eloquent\SoftDeletes;
class Payment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'order_id',
        'user_id',
        'payment_method',
        'transaction_id',
        'stripe_session_id',
        'stripe_payment_intent',
        'amount',
        'status',
        'paid_at',
    ];
 
    protected $casts = [
        'amount'  => 'decimal:2',
        'paid_at' => 'datetime',
        'status'  => PaymentStatus::class,
    ];
 
    /* ------------------------------------------------------------------ */
    /*  Relationships                                                       */
    /* ------------------------------------------------------------------ */
 
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
 
    /* ------------------------------------------------------------------ */
    /*  Scopes                                                              */
    /* ------------------------------------------------------------------ */
 
    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }
 
        return $query->where(function ($q) use ($term) {
            $q->where('transaction_id', 'like', "%{$term}%")
              ->orWhere('stripe_session_id', 'like', "%{$term}%")
              ->orWhere('stripe_payment_intent', 'like', "%{$term}%")
              ->orWhereHas('order', fn ($o) => $o->where('order_number', 'like', "%{$term}%"));
        });
    }
 
    public function scopeByStatus($query, ?string $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }
 
    public function scopeByMethod($query, ?string $method)
    {
        return $method ? $query->where('payment_method', $method) : $query;
    }    
}
