<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class SectionService extends Model
{
    use  HasTranslations;

    protected $appends=['image','title_translate','details_translate','button_translate'];
    protected $translatable = ['title','button','details'];
    protected $guarded = [];
    const PATH_IMAGE='/upload/services/images/';

    //Relations
    public function imageService()
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
    public function getButtonTranslateAttribute()
    {
        return @$this->button;
    }


    public function getImageAttribute()
    {
        return !is_null(@$this->imageService->path) ? asset(Storage::url(@$this->imageService->path) ): '';
    }
}
