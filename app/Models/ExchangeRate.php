<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $fillable = [
        'from_currency',
        'to_currency',
        'rate',
        'effective_date',
        'is_active',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'is_active' => 'boolean',
        'rate' => 'decimal:2',
    ];

    // public function scopeActive($query)
    // {
    //     return $query->where('is_active', true);
    // }

    // public function scopeCurrent($query)
    // {
    //     return $query->where('effective_date', '<=', now()->toDateString())
    //                 ->orderBy('effective_date', 'desc');
    // }
}
