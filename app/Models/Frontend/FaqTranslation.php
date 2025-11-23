<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class FaqTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'faq_translations';

    protected $fillable = [
        'faq_id',
        'locale',
        'question',
        'answer',
        'created_by',
        'updated_by',
    ];

    public function faq()
    {
        return $this->belongsTo(Faq::class);
    }
}
