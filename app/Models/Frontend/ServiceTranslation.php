<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class ServiceTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'service_id',
        'locale',
        'title',
        'short_description',
        'description',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the service this translation belongs to.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
