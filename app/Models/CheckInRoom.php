<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckInRoom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'check_in_id',
        'room_id',
        'room_price',
    ];

    protected $casts = [
        'room_price' => 'decimal:2',
    ];

    public function checkIn()
    {
        return $this->belongsTo(CheckIn::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
