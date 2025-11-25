<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductFeatureDetail extends Model
{
    use SoftDeletes;

    protected $table = 'product_feature_details';

    protected $fillable = [
        'product_feature_id',
        'icon',
        'sort',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'sort' => 'integer',
    ];

    public function feature()
    {
        return $this->belongsTo(ProductFeature::class, 'product_feature_id');
    }

    public function translations()
    {
        return $this->hasMany(ProductFeatureDetailTranslation::class, 'product_feature_detail_id');
    }
    public function getName($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations()->where('locale', $locale)->first()?->title ?? null;
    }
    public function getDescription($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations()->where('locale', $locale)->first()?->description ?? null;
    }
}
