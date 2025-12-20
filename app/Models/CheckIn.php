<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\Loggable;

class CheckIn extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = [
        'booking_number',
        'room_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'guest_national_id',
        'guest_passport',
        'guest_type',
        'guest_country',
        'number_of_guests',
        'check_in_date',
        'check_out_date',
        'total_amount',
        'paid_amount',
        'status',
        'notes',
        'actual_check_in_at',
        'actual_check_out_at',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'actual_check_in_at' => 'datetime',
        'actual_check_out_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($checkIn) {
            if (empty($checkIn->booking_number)) {
                $lastBooking = static::latest('id')->first();
                $nextNumber = $lastBooking ? intval(substr($lastBooking->booking_number, 2)) + 1 : 1;
                $checkIn->booking_number = 'BK' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['confirmed', 'checked_in']);
    }

    public function scopeCheckedIn($query)
    {
        return $query->where('status', 'checked_in');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getIsPaidAttribute()
    {
        return $this->remaining_amount <= 0;
    }
}
