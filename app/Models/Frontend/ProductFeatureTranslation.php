<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class ProductFeatureTranslation extends Model
{
    protected $table = 'product_feature_translations';

    protected $fillable = [
        'product_feature_id',
        'locale',
        'title',
        'description',
        'created_by',
        'updated_by',
    ];
    
    public function feature()
    {
        return $this->belongsTo(ProductFeature::class, 'product_feature_id');
    }
}