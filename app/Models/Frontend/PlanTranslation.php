<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class PlanTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'plan_id',
        'locale',
        'name',
        'created_by',
        'updated_by',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
