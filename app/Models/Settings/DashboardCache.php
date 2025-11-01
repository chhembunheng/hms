<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DashboardCache extends Model
{
    use SoftDeletes;

    protected $table = 'dashboard_caches';

    protected $fillable = [
        'key',
        'locale',
        'payload',
        'expires_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'payload' => 'array',
        'expires_at' => 'datetime',
    ];
}
