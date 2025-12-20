<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class RoomType extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = [
        'name_en',
        'name_kh',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getLocalizedNameAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'km' && $this->name_kh) {
            return $this->name_kh;
        }
        return $this->name_en ?: $this->name_kh;
    }
}
