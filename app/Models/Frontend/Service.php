<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $table = 'services';

    protected $fillable = [
        'slug',
        'icon',
        'image',
        'images',
        'sort',
        'is_slider',
        'slider_image',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_slider' => 'boolean',
        'images' => 'collection',
    ];

    public function navigations()
    {
        return $this->morphMany(Navigation::class, 'linked');
    }


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
        return $this->translations->where('locale', $locale)->first()?->name ?? 'N/A';
    }

    /**
     * Get the description for a specific locale.
     */
    public function getDescription($locale = 'en')
    {
        return $this->translations->where('locale', $locale)->first()?->description ?? '';
    }
    /**
     * Get the content for a specific locale.
     */
    public function getContent($locale = 'en')
    {
        return $this->translations->where('locale', $locale)->first()?->content ?? '';
    }
    // get route
    public function getRoute()
    {
        return 'services';
    }
    // slider title
    public function getSliderTitle($locale = 'en')
    {
        return $this->translations->where('locale', $locale)->first()?->slider_title ?? '';
    }
    /**
     * Get the slider description for a specific locale.
     */
    public function getSliderDescription($locale = 'en')
    {
        return $this->translations->where('locale', $locale)->first()?->slider_description ?? '';
    }

    /**
     * Get the slug for a specific locale.
     */
    public function getSlug($locale = 'en')
    {
        return $this->slug ?? '';
    }
}
