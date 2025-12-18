<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_number',
        'floor',
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
