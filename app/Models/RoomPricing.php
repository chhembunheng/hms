<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomPricing extends Model
{
    protected $table = 'room_pricing';

    protected $fillable = [
        'room_type_id',
        'price',
        'currency',
        'effective_from',
        'effective_to',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the room type that owns the pricing.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Scope a query to only include active pricing.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include pricing effective for a given date.
     */
    public function scopeEffectiveOn($query, $date)
    {
        return $query->where(function ($q) use ($date) {
            $q->where('effective_from', '<=', $date)
              ->where(function ($subQ) use ($date) {
                  $subQ->whereNull('effective_to')
                       ->orWhere('effective_to', '>=', $date);
              });
        });
    }
}
