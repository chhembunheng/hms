<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use App\Traits\Loggable;

class Menu extends Model
{
    use SoftDeletes, Loggable;

    protected $table = 'menus';

    protected $fillable = [
        'icon',
        'sort',
        'route',
        'parent_id',
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

        // Clear menu cache when menu is created, updated, or deleted
        static::saved(function () {
            self::clearMenuCache();
        });

        static::deleted(function () {
            self::clearMenuCache();
        });
    }

    /**
     * Clear all menu-related caches
     */
    public static function clearMenuCache()
    {
        // Clear raw menu data
        Cache::forget('menus_raw_data');
        
        // Clear processed menus for all locales
        // Since we can't use wildcards easily, we'll clear entire cache
        // Or you can implement cache tags if using Redis/Memcached
        Cache::flush();
    }

    public function translations()
    {
        return $this->hasMany(MenuTranslation::class, 'menu_id');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'menu_id');
    }
}
