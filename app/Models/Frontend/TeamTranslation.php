<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamTranslation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'locale',
        'name',
        'position_name',
        'bio',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
