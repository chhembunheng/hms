<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class ExchangeRate extends Model
{
    use SoftDeletes, Loggable;

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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        return $query->where('effective_date', '<=', now()->toDateString())
                    ->orderBy('effective_date', 'desc');
    }

    public static function getCurrentRate(string $from = 'USD', string $to = 'KHR'): float
    {
        $rate = static::where('from_currency', $from)
            ->where('to_currency', $to)
            ->where('is_active', true)
            ->where('effective_date', '<=', now()->toDateString())
            ->orderBy('effective_date', 'desc')
            ->value('rate');

        return $rate ? (float)$rate : 4100.00;
    }
}
