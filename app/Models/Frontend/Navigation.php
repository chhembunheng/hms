<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Navigation extends Model
{
    use SoftDeletes;

    protected $table = 'navigations';

    protected $fillable = [
        'parent_id',
        'url',
        'slug',
        'icon',
        'sort',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'sort' => 'integer',
    ];


    public function linked()
    {
        return $this->morphTo();
    }

    public function translations()
    {
        return $this->hasMany(NavigationTranslation::class, 'navigation_id');
    }

    public function parent()
    {
        return $this->belongsTo(Navigation::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Navigation::class, 'parent_id');
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

    public function getLabel($locale = null)
    {
        return $this->getTranslation($locale)?->label ?? null;
    }
}
