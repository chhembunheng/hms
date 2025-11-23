<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    protected $table = 'tags';

    protected $fillable = [
        'icon',
        'sort',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Get the translations for this tag.
     */
    public function translations()
    {
        return $this->hasMany(TagTranslation::class);
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
}
