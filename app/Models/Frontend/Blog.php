<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;

    protected $table = 'blogs';

    protected $fillable = [
        'slug',
        'author_id',
        'image',
        'images',
        'is_published',
        'sort',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'images' => 'collection',
    ];

    public function translations()
    {
        return $this->hasMany(BlogTranslation::class);
    }

    public function getTranslation($locale = 'en')
    {
        return $this->translations->where('locale', $locale)->first();
    }

    public function getTitle($locale = 'en')
    {
        return $this->getTranslation($locale)?->title ?? 'N/A';
    }

    public function getSlug($locale = 'en')
    {
        return $this->slug ?? '';
    }

    public function seoMetas()
    {
        return $this->morphMany(SeoMeta::class, 'seoable');
    }

    public function getSeoMeta(string $locale)
    {
        return $this->seoMetas->firstWhere('locale', $locale);
    }
}
