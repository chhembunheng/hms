<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'icon',
        'image',
        'sort',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Get the translations for this service.
     */
    public function translations()
    {
        return $this->hasMany(ServiceTranslation::class);
    }

    /**
     * Get the name for a specific locale.
     */
    public function getName($locale = 'en')
    {
        return $this->translations->where('locale', $locale)->first()?->title ?? 'N/A';
    }

    /**
     * Get the description for a specific locale.
     */
    public function getDescription($locale = 'en')
    {
        return $this->translations->where('locale', $locale)->first()?->description ?? '';
    }

    /**
     * Get the slug for a specific locale.
     */
    public function getSlug($locale = 'en')
    {
        return $this->slug ?? '';
    }
}
