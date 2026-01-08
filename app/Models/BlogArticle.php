<?php

namespace App\Models;

use App\Models\Upload;
use App\Models\Keyword;
use App\Models\BlogCategory;
use App\Models\BlogInteraction;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogArticle extends Model
{
    use HasFactory;

    const PATH_IMAGE = "/upload/Blog/Article/";

    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];


    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'blog_category_id',
        'created_by',
        'status',
        'meta_description',
        'meta_title',
        'slug',
        'locale',
        'created_by',
        'updated_by',
    ];



    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    // public function interactions()
    // {
    //     return $this->hasMany(BlogInteraction::class);
    // }

    // public function comments()
    // {
    //     return $this->hasMany(ArticleComment::class)->whereNull('parent_id')->with('replies');
    // }
    // public function replies()
    // {
    //     return $this->hasMany(ArticleComment::class, 'parent_id')->with('replies');
    // }


    // public function likes()
    // {
    //     return $this->morphMany(ArticleLike::class, 'likeable');
    // }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function images()
    {
        return $this->morphMany(Upload::class, 'relation')->select('id', 'path', 'relation_id', 'full_original_path', 'relation_type');
    }

    // public function keywords()
    // {
    //     return $this->morphMany(Keyword::class, 'section');
    // }
    public function getImagePathsAttribute()
    {
        return $this->images->pluck('full_original_path')->toArray();
    }

    public function getTranslatedKeywordsAttribute()
    {
        return $this->keywords->map(function ($keyword) {
            return $keyword->translated_name;
        })->toArray();
    }
}
