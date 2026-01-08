<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class SectionServiceItems extends Model
{
    use  HasTranslations;

    protected $appends=['image'];
    protected $translatable = ['title','details','button'];
    protected $guarded = [];
    const PATH_IMAGE='/upload/services/images/';

    //Relations
    public function imageServiceitem()
    {
        return $this->morphOne(Upload::class, 'imageable');
    }


    public function getImageAttribute()
    {
        return !is_null(@$this->imageServiceitem->path) ? asset(Storage::url(@$this->imageServiceitem->path) ): '';
    }
}
