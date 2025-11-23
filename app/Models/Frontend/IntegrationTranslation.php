<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class IntegrationTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'integration_translations';

    protected $fillable = [
        'integration_id',
        'locale',
        'name',
        'description',
        'created_by',
        'updated_by',
    ];

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
}
