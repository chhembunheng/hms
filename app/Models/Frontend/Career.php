<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Career extends Model
{
    use SoftDeletes;

    protected $table = 'careers';

    protected $fillable = [
        'slug',
        'location',
        'deadline',
        'type',
        'level',
        'priority',
        'sort',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'deadline' => 'date',
        'sort' => 'integer',
        'is_active' => 'boolean',
    ];

    public function translations()
    {
        return $this->hasMany(CareerTranslation::class, 'career_id');
    }

    public function getTranslation($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
    }

    public function getTitle($locale = null)
    {
        return $this->getTranslation($locale)?->title ?? 'N/A';
    }

    public function getSlug($locale = null)
    {
        return $this->slug;
    }

    public function getShortDescription($locale = null)
    {
        return $this->getTranslation($locale)?->content;
    }

    public function getDescription($locale = null)
    {
        return $this->getTranslation($locale)?->description;
    }
}