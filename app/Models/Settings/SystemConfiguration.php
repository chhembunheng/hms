<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class SystemConfiguration extends Model
{
    use SoftDeletes, Loggable;

    protected $table = 'system_configurations';

    protected $fillable = [
        'hotel_name_en',
        'hotel_name_kh',
        'location_en',
        'location_kh',
        'phone_number',
        'email',
        'watermark_title',
        'system_title',
        'logo_path',
        'favicon_path',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the localized hotel name
     */
    public function getLocalizedHotelNameAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'km') {
            return $this->hotel_name_kh ?? $this->hotel_name_en;
        }
        return $this->hotel_name_en;
    }

    /**
     * Get the localized location
     */
    public function getLocalizedLocationAttribute()
    {
        $locale = app()->getLocale();
        return $this->{'location_' . $locale} ?? $this->location_en;
    }
}
