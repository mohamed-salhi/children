<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SectionJourneyItem extends Model
{
    use  HasTranslations;

    protected $guarded=[];
    protected $translatable = ['item'];


}
