<?php

namespace App\Models\Settings;

use App\Models\Settings\User;
use Illuminate\Database\Eloquent\Model;

class UserTranslation extends Model
{
    protected $table = 'user_translations';

    protected $fillable = [
        'user_id',
        'locale',
        'first_name',
        'last_name',
        'description',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
