<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class SectionJourney extends Model
{
    use  HasTranslations;

    protected $guarded = [];
    protected $appends=['image_1','image_2','image_3','image_4'];
    protected $translatable = ['title', 'details'];

    const PATH_IMAGE = '/upload/content/images/';


    public function imageOneJourney()
    {
        return $this->morphOne(Upload::class, 'imageable')->where('name', '=', 'imageOneJourney');
    }

    public function imageTowJourney()
    {
        return $this->morphOne(Upload::class, 'imageable')->where('name', '=', 'imageTowJourney');
    }

    public function imageThreeJourney()
    {
        return $this->morphOne(Upload::class, 'imageable')->where('name', '=', 'imageThreeJourney');
    }

    public function imageFourJourney()
    {
        return $this->morphOne(Upload::class, 'imageable')->where('name', '=', 'imageFourJourney');
    }

    public function items()
    {
        return $this->hasMany(SectionJourneyItem::class, 'section_journey_id');
    }


    public function getImage1Attribute()
    {
        return !is_null(@$this->imageOneJourney->path) ? asset(Storage::url(@$this->imageOneJourney->path) ): url('/') . '/dashboard/app-assets/images/4367.jpg';
    }
    public function getImage2Attribute()
    {
        return !is_null(@$this->imageTowJourney->path) ? asset(Storage::url(@$this->imageTowJourney->path) ): url('/') . '/dashboard/app-assets/images/4367.jpg';
    }
    public function getImage3Attribute()
    {
        return !is_null(@$this->imageThreeJourney->path) ? asset(Storage::url(@$this->imageThreeJourney->path) ): url('/') . '/dashboard/app-assets/images/4367.jpg';
    }
    public function getImage4Attribute()
    {
        return !is_null(@$this->imageFourJourney->path) ? asset(Storage::url(@$this->imageFourJourney->path) ): url('/') . '/dashboard/app-assets/images/4367.jpg';
    }

}
