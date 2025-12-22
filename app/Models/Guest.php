<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\Loggable;

class Guest extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'email',
        'phone',
        'national_id',
        'passport',
        'guest_type',
        'country',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'postal_code',
        'emergency_contact_name',
        'emergency_contact_phone',
        'notes',
        'last_visit_at',
        'total_visits',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'last_visit_at' => 'datetime',
        'total_visits' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($guest) {
            $guest->full_name = trim($guest->first_name . ' ' . $guest->last_name);
        });

        static::updating(function ($guest) {
            $guest->full_name = trim($guest->first_name . ' ' . $guest->last_name);
        });
    }

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class);
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    public function getDisplayNameAttribute()
    {
        return $this->full_name ?: ($this->first_name . ' ' . $this->last_name);
    }
}
