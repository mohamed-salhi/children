<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class SectionFeatures extends Model
{
    use  HasTranslations;

    protected $appends=['image','title_translate','details_translate'];
    protected $translatable = ['title','details'];
    protected $guarded = [];
    const PATH_IMAGE='/upload/features/images/';

    //Relations
    public function imageFeatures()
    {
        return $this->morphOne(Upload::class, 'imageable');
    }

    //Attributes
    public function getTitleTranslateAttribute()
    {
        return @$this->title;
    }
    public function getDetailsTranslateAttribute()
    {
        return @$this->details;
    }



    public function getImageAttribute()
    {
        return !is_null(@$this->imageFeatures->path) ? asset(Storage::url(@$this->imageFeatures->path) ): '';
    }
}
