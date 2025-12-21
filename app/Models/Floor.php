<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class Floor extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = [
        'floor_number',
        'name_en',
        'name_kh',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'floor_id', 'id');
    }

    public function getLocalizedNameAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'km' ? $this->name_kh : $this->name_en;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
