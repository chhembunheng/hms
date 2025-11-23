<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    protected $table = 'product_translations';

    protected $fillable = [
        'product_id',
        'locale',
        'name',
        'description',
        'content',
        'created_by',
        'updated_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
