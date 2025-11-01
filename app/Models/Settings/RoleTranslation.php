<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class RoleTranslation extends Model
{
    protected $table = 'role_translations';

    protected $fillable = [
        'role_id',
        'locale',
        'name',
        'description',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
