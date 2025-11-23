<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class BlogTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'blog_translations';

    protected $fillable = [
        'blog_id',
        'locale',
        'title',
        'excerpt',
        'body',
        'created_by',
        'updated_by',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
