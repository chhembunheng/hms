<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class TagTranslation extends Model
{
    protected $table = 'tag_translations';

    protected $fillable = [
        'tag_id',
        'locale',
        'name',
        'description',
        'created_by',
        'updated_by',
    ];

    public $timestamps = true;

    /**
     * Get the tag that owns this translation.
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
