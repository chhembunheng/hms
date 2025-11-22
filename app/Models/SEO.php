<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SEO extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'seo_meta';

    protected $fillable = [
        'model_type',
        'model_id',
        'locale',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'slug',
        'canonical_url',
        'seo_score',
        'is_published',
        'seo_status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'seo_score' => 'integer'
    ];
}