<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Integration extends Model
{
    use SoftDeletes;

    protected $table = 'integrations';

    protected $fillable = [
        'image',
        'images',
        'sort',
        'slug',
        'icon',
        'parent_id',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'images' => 'collection',
    ];

    public function navigations()
    {
        return $this->morphMany(Navigation::class, 'linked');
    }

    public function children()
    {
        return $this->hasMany(Integration::class, 'parent_id');
    }

    public function translations()
    {
        return $this->hasMany(IntegrationTranslation::class);
    }

    public function getTranslation($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    public function getName($locale = null)
    {
        return $this->getTranslation($locale)?->name ?? '';
    }

    public function getDescription($locale = null)
    {
        return $this->getTranslation($locale)?->description ?? '';
    }
}
