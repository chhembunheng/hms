<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class PermissionTranslation extends Model
{
    protected $table = 'permission_translations';

    protected $fillable = [
        'permission_id',
        'locale',
        'name',
        'description',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
