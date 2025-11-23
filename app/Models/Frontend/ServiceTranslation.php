<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class ServiceTranslation extends Model
{
    protected $table = 'service_translations';
    
    protected $fillable = [
        'service_id',
        'locale',
        'name',
        'content',
        'description',
        'slider_title',
        'slider_description',
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
