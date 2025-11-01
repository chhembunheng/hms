<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class MenuTranslation extends Model
{
    protected $table = 'menu_translations';

    protected $fillable = [
        'menu_id',
        'locale',
        'name',
        'description',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
