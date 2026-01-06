<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;


class Product extends Model
{
    use HasFactory, HasTranslations;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $translatable = ['name','details'];
    protected $appends = ['name_translate','attachments','category_name','details_translate'];
    protected $guarded = [];
    const PATH_IMAGE='/upload/products/images/';
//Relations
    public function category(){
        return $this->belongsTo(Category::class,'category_uuid');
    }
    public function sizes(){
        return $this->hasMany(ProductSize::class,'product_uuid');
    }
    public function imageProduct()
    {
        return $this->morphMany(Upload::class, 'imageable');
    }
    //Attributes
    public function getCategoryNameAttribute()
    {
        return @$this->category->name;
    }
    public function getNameTranslateAttribute()
    {
        return @$this->name;
    }
    public function getDetailsTranslateAttribute()
    {
        return @$this->details;
    }
    public function getAttachmentsAttribute()
    {
        $attachments = [];
        foreach ($this->imageProduct as $item) {
            $attachments[] = [
                'uuid' => $item->uuid,
                'attachment' => !is_null(@$item->path) ? asset(Storage::url(@$item->path)) : null,
            ];
        }
        return $attachments;
    }

    //boot
    public static function boot()
    {
        parent::boot();
        self::creating(function ($item) {
            $item->uuid = Str::uuid();

            // احصل على السنة الحالية
            $year = date('Y');

            // احصل على آخر منتج تم إنشاؤه في نفس السنة
            $lastProduct = DB::table('products')
                ->where('number', 'LIKE', "{$year}%") // إصلاح البحث
                ->orderBy('number', 'desc') // إضافة الترتيب
                ->first();

            // استخراج الرقم التسلسلي وزيادته
            $lastNumber = $lastProduct ? intval(substr($lastProduct->number, -4)) : 0;
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            // تعيين الكود الجديد للمنتج بدون "-"
            $item->number = "{$year}{$newNumber}";
        });
        static::addGlobalScope('status', function (Builder $builder) {
            $builder->where('status', 1);//1==active
        });

    }

}
