<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Loggable;

class Service extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = [
        'code',
        'name_en',
        'name_kh',
        'category',
        'price',
        'unit',
        'is_active',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getDisplayNameAttribute(): string
    {
        if (app()->getLocale() === 'km' && !empty($this->name_kh)) {
            return $this->name_kh;
        }
        return $this->name_en;
    }

    public function checkInServices(): HasMany
    {
        return $this->hasMany(CheckInService::class);
    }
}
