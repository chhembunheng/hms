<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class ProductFeatureDetailTranslation extends Model
{
    protected $table = 'product_feature_detail_translations';

    protected $fillable = [
        'product_feature_detail_id',
        'locale',
        'title',
        'description',
        'created_by',
        'updated_by',
    ];
    
    public function detail()
    {
        return $this->belongsTo(ProductFeatureDetail::class, 'product_feature_detail_id');
    }
}
