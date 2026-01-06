<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{

    use HasFactory, HasTranslations;

    protected $translatable = ['value'];
    protected $guarded = [];


 public function imageClient()
 {
     return $this->morphMany(Upload::class, 'imageable');
 }

    public function getOpeningTranslateAttribute()
    {
        return @$this->opening;
    }
    public function getAddressTranslateAttribute()
    {
        return @$this->address;
    }
      public function getAttachmentsAttribute()
      {
          $attachments = [];
          foreach ($this->imageClient as $item) {
              $attachments[] = [
                  'uuid' => $item->uuid,
                  'attachment' => !is_null(@$item->path) ? asset(Storage::url(@$item->path)) : null,
              ];
          }
          return $attachments;
      }

}
