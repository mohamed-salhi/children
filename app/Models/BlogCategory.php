<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogCategory extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name', 'slug', 'description', 'status', 'updated_by', 'created_by'];
    protected $translatable = ['name', 'description', 'slug'];
    protected $hidden = ['created_at', 'updated_at', 'updated_by', 'created_by', 'description'];
    protected $appends = ['name_translate'];
    public function getNameTranslateAttribute()
    {
        return @$this->name;
    }
    // public function articles()
    // {
    //     return $this->hasMany(BlogArticle::class, 'blog_category_id');
    // }
}
