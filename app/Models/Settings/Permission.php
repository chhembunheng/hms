<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
        'action_route',
        'icon',
        'target',
        'action',
        'slug',
        'order',
        'menu_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * Boot the model and add event listeners
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saved(function () {
            Menu::clearMenuCache();
        });

        static::deleted(function () {
            Menu::clearMenuCache();
        });
    }

    public function translations()
    {
        return $this->hasMany(PermissionTranslation::class, 'permission_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id');
    }
}
