<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class SectionJourney extends Model
{
    use  HasTranslations;
    protected $guarded = [];
    protected $appends=['attachments'];
    protected $translatable = ['title','details'];

    const PATH_IMAGE='/upload/content/images/';

    public function imageJourney()
    {
        return $this->morphMany(Upload::class, 'imageable');
    }
    public function items()
    {
        return $this->hasMany(SectionJourneyItem::class, 'section_journey_id');
    }


    public function getAttachmentsAttribute()
    {
        $attachments = [];
        foreach ($this->imageJourney as $item) {
            $attachments[] = [
                'uuid' => $item->uuid,
                'attachment' => !is_null(@$item->path) ? asset(Storage::url(@$item->path)) : null,
            ];
        }
        return $attachments;
    }

}
