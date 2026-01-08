<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class SectionHero extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $appends=['image'];
    protected $translatable = ['title','button','details'];

    const PATH_IMAGE='/upload/content/images/';

    public function imageHero()
    {
        return $this->morphOne(Upload::class, 'imageable');
    }


    public function getImageAttribute()
    {
        return !is_null(@$this->imageHero->path) ? asset(Storage::url(@$this->imageHero->path) ): '';
    }


}
