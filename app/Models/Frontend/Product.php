<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'image',
        'icon',
        'sort',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'sort' => 'integer',
    ];

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class, 'product_id');
    }

    public function features()
    {
        return $this->hasMany(ProductFeature::class, 'product_id');
    }

    public function getTranslation($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    public function getName($locale = null)
    {
        return $this->getTranslation($locale)?->name ?? 'N/A';
    }

    public function getSlug($locale = null)
    {
        return $this->slug ?? null;
    }

    public function getDescription($locale = null)
    {
        return $this->getTranslation($locale)?->description ?? null;
    }

    public function getShortDescription($locale = null)
    {
        return $this->getTranslation($locale)?->short_description ?? null;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag', 'product_id', 'tag_id');
    }
}
