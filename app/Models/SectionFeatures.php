<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class SectionFeatures extends Model
{
    use  HasTranslations;

    protected $appends=['image'];
    protected $translatable = ['title'];
    protected $guarded = [];
    const PATH_IMAGE='/upload/features/images/';

    //Relations
    public function imageFeatures()
    {
        return $this->morphOne(Upload::class, 'imageable');
    }

    public function items()
    {
        return $this->hasMany(SectionFeaturesItems::class, 'section_feature_id');
    }



    public function getImageAttribute()
    {
        return !is_null(@$this->imageFeatures->path) ? asset(Storage::url(@$this->imageFeatures->path) ): '';
    }
}
