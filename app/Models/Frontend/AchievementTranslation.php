<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class AchievementTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'achievement_translations';

    protected $fillable = [
        'achievement_id',
        'locale',
        'title',
        'suffix',
        'created_by',
        'updated_by',
    ];

    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }
}
