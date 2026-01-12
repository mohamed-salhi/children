<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class SectionContact extends Model
{
    use  HasTranslations;
    protected $guarded = [];
    protected $appends=['image'];
    protected $translatable = ['title','details'];

    const PATH_IMAGE='/upload/content/images/';

    public function imageContact()
    {
        return $this->morphOne(Upload::class, 'imageable');
    }


    public function getImageAttribute()
    {
        return !is_null(@$this->imageContact->path) ? asset(Storage::url(@$this->imageContact->path) ): '';
    }

}
