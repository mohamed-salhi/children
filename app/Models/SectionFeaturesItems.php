<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class SectionFeaturesItems extends Model
{
    use HasTranslations;

    protected $guarded=[];
    protected $appends=['image'];

    protected $translatable = ['title','details'];

    const PATH_IMAGE='/upload/features/images/';
    public function imageFeaturesItem()
    {
        return $this->morphOne(Upload::class, 'imageable');
    }


    public function getImageAttribute()
    {
        return !is_null(@$this->imageFeaturesItem->path) ? asset(Storage::url(@$this->imageFeaturesItem->path) ): '';
    }
}
