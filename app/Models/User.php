<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRoleEnum;
use App\Models\Profile;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable ,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'address',
        'email',

        'role',
        'email_verified_at',
        'avatar',
        'provider',
        'provider_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' =>  UserRoleEnum::class,
        ];
    }
        // ── Scopes ─────────────────────────────────
    public function scopeFilter($query, $filters)
    {        // search
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%")
                  ->orWhere('phone', 'like', "%{$filters['search']}%");
            });
        }
        // verified
        if (isset($filters['verified']) && $filters['verified'] !== '') {
            $filters['verified']
                ? $query->whereNotNull('email_verified_at')
                : $query->whereNull('email_verified_at');
        }
        // provider
        if (!empty($filters['provider'])) {
            $query->where('provider', $filters['provider']);
        }
        return $query;
    }
        // ── accessors ─────────────────────────────────
    public function getOrdersCountAttribute()
    {   
            return $this->hasMany(Order::class)->count();
    }   
    // orders_sum_total
    public function getOrdersSumTotalAttribute()
    {
        return $this->hasMany(Order::class)->sum('total_amount');
    }
        // ── Relationships ─────────────────────────────────

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
