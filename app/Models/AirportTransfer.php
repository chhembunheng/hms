<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Loggable;

class AirportTransfer extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = [
        'check_in_id',
        'guest_id',
        'guest_name',
        'guest_phone',
        'transfer_type',
        'flight_number',
        'transfer_datetime',
        'vehicle_type',
        'pickup_location',
        'dropoff_location',
        'passenger_count',
        'luggage_count',
        'driver_name',
        'driver_phone',
        'price',
        'is_charged_to_room',
        'status',
        'notes',
    ];

    protected $casts = [
        'transfer_datetime' => 'datetime',
        'price' => 'decimal:2',
        'is_charged_to_room' => 'boolean',
        'passenger_count' => 'integer',
        'luggage_count' => 'integer',
    ];

    public function checkIn(): BelongsTo
    {
        return $this->belongsTo(CheckIn::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }
}
