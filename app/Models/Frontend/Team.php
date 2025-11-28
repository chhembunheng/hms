<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

    protected $table = 'teams';

    protected $fillable = [
        'photo',
        'linkedin_url',
        'sort',
        'twitter_url',
        'facebook_url',
        'instagram_url',
        'github_url',
        'position_id',
        'email',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort' => 'integer',
    ];

    public function translations()
    {
        return $this->hasMany(TeamTranslation::class);
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

    public function getPosition($locale = null)
    {
        $positions = config('init.positions')[$this->position_id] ?? null;
        if ($positions) {
            return $positions[$locale ?? app()->getLocale()] ?? $positions['en'];
        }
        return '';
    }

    public function getBio($locale = null)
    {
        return $this->getTranslation($locale)?->bio ?? '';
    }
}
