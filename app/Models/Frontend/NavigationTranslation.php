<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NavigationTranslation extends Model
{
    use SoftDeletes;

    protected $table = 'navigation_translations';

    protected $fillable = [
        'navigation_id',
        'locale',
        'name',
        'label',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function navigation()
    {
        return $this->belongsTo(Navigation::class, 'navigation_id');
    }
}
