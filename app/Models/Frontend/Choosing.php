<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Choosing extends Model
{
    use SoftDeletes;

    protected $table = 'choosings';

    protected $fillable = [
        'image',
        'sort',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'sort' => 'integer',
        'is_active' => 'boolean',
    ];

    public function translations()
    {
        return $this->hasMany(ChoosingTranslation::class, 'choosing_id');
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

    public function getDescription($locale = null)
    {
        return $this->getTranslation($locale)?->description;
    }
}