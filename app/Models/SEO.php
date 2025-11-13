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
        'meta_title',
        'meta_description',
        'meta_keywords',
        'seo_score',
        'is_published',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'seo_score' => 'integer',
    ];
}