<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class Room extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = [
        'room_number',
        'floor_id',
        'room_type_id',
        'status_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function status()
    {
        return $this->belongsTo(RoomStatus::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
