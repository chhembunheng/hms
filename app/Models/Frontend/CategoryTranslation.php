<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    protected $table = 'category_translations';

    protected $fillable = [
        'category_id',
        'locale',
        'name',
        'description',
        'created_by',
        'updated_by',
    ];

    public $timestamps = true;

    /**
     * Get the category that owns this translation.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
