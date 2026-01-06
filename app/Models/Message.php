<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['type_text','content'];

    const PATH_IMAGE = "/upload/messages/images/";
    const PATH_VOICE = "/upload/messages/voices/";
//    protected $hidden = [
//        'message', 'lat_lng', 'images', 'attachment', 'voice','updated_at','view_user','view_admin'
//    ];
    const TEXT = 1;
    const ATTACHMENT = 2;
    const VOICE = 3;
    const LOCATION = 4;
    const IMAGE = 5;

    //Relations

    public function sender()
    {
        return $this->belongsTo(Admin::class, 'admin_sender');
    }
    public function receiver()
    {
        return $this->belongsTo(Admin::class, 'admin_receiver');
    }

    public function voice()
    {
        return $this->morphOne(Upload::class, 'imageable')->where('type', '=', Upload::VOICE);
    }

    public function images()
    {
        return $this->morphOne(Upload::class, 'imageable')->where('type', Upload::IMAGE);
    }



    public function getTypeTextAttribute()
    {
        if ($this->type == self::TEXT) {
            return 'text';
        } elseif ($this->type == self::IMAGE) {
            return 'image';
        } elseif ($this->type == self::VOICE) {
            return 'voice';
        } elseif ($this->type == self::LOCATION) {
            return 'location';
        } elseif ($this->type == self::ATTACHMENT) {
            return 'attachment';
        }
    }

    //Attributes

}
