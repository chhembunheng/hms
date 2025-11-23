<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class ClientTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'client_translations';

    protected $fillable = [
        'client_id',
        'locale',
        'name',
        'description',
        'created_by',
        'updated_by',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
