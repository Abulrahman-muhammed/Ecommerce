<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\OrderItem;
use App\Models\User; 
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'payment_method',
        'status',
        'payment_status',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'total_amount'
    ];
    // cast status enum
    protected $casts = [
        'status' => OrderStatusEnum::class,
        'payment_status' => PaymentStatusEnum::class,
    ];
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Guest User'
        ]);
    }
    // payment relation
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->order_number = self::getNextOrderNumber();
        });
    }
    // get next order number
    public static function getNextOrderNumber()
    {       
        $year = Carbon::now()->year;

        $lastOrder = self::whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->order_number, -5); // get last 5 digits as number
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'ORD-' . $year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT); // format: ORD-2024-00001 5=> 00001 '0' => pad with zeros, STR_PAD_LEFT => pad on the left
    }

        // ------------------ [Filter Scope] ------------------
    public function scopeFilter($query, $filters)
    {
    if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        //  order status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        //  payment status
        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

    }
    // ------------------ [Customer Name accessor ] ------------------
    public function getCustomerNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
