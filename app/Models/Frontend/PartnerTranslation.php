<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class PartnerTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'partner_id',
        'locale',
        'name',
        'description',
        'created_by',
        'updated_by',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
