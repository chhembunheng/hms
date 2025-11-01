<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class CareerTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'career_id',
        'locale',
        'title',
        'short_description',
        'description',
        'created_by',
        'updated_by',
    ];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }
}
