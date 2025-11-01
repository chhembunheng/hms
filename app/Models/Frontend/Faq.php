<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id',
        'slug',
        'is_published',
        'sort',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function translations()
    {
        return $this->hasMany(FaqTranslation::class);
    }

    public function parent()
    {
        return $this->belongsTo(Faq::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Faq::class, 'parent_id');
    }

    public function getTranslation($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    public function getQuestion($locale = null)
    {
        return $this->getTranslation($locale)?->question ?? 'N/A';
    }

    public function getAnswer($locale = null)
    {
        return $this->getTranslation($locale)?->answer ?? '';
    }

    public function getSlug()
    {
        return $this->slug ?? '';
    }
}