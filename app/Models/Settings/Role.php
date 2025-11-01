<?php

namespace App\Models\Settings;

use App\Models\Settings\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'administrator',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function translations()
    {
        return $this->hasMany(RoleTranslation::class, 'role_id');
    }

    public function permissions()
    {
        // Pivot table created by migration: role_permission
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }

    public function users()
    {
        // Pivot table created by migration: user_role
        return $this->belongsToMany(User::class, 'user_role', 'role_id', 'user_id');
    }
}
