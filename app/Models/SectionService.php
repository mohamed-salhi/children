<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class SectionService extends Model
{
    use  HasTranslations;

    protected $appends=['image'];
    protected $translatable = ['title'];
    protected $guarded = [];
    const PATH_IMAGE='/upload/services/images/';

    //Relations
    public function imageService()
    {
        return $this->morphOne(Upload::class, 'imageable');
    }

    public function items()
    {
        return $this->hasMany(SectionServiceItems::class, 'section_service_id')->orderBy('id');
    }



    public function getImageAttribute()
    {
        return !is_null(@$this->imageService->path) ? asset(Storage::url(@$this->imageService->path) ): '';
    }
}
