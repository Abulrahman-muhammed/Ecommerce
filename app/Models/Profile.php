<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'bio',
        'birth_date',
        'gender',
        'phone',
        'address',
        'city',
        'country',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The profile's avatar — stored via the polymorphic images table.
     */
    public function avatarImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->where('usage', 'avatar');
    }

    // ── Accessors ──────────────────────────────────────────────────

    /**
     * Full name: "Ahmed Hassan"
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }


    /**
     * One-line location string: "Cairo, Egypt"
     */
    public function getLocationAttribute(): ?string
    {
        return collect([$this->city, $this->country])
            ->filter()
            ->implode(', ') ?: null;
    }
}