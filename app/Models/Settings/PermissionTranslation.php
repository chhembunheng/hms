<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionTranslation extends Model
{
    use SoftDeletes;

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
    // track who created, updated, deleted the record
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });
        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });
        static::deleting(function ($model) {
            $model->deleted_by = auth()->id();
            $model->save();
        });
    }
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
