<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class ChoosingTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'choosing_translations';

    protected $fillable = [
        'choosing_id',
        'locale',
        'title',
        'description',
        'created_by',
        'updated_by',
    ];

    public function choosing()
    {
        return $this->belongsTo(Choosing::class);
    }
}
