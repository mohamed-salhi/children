<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
<<<<<<< HEAD
    use HasFactory, Notifiable ,HasApiTokens;
=======
    use HasFactory, Notifiable;
>>>>>>> 278d07bbeded43eed01bbcb0228afc9d136270ea

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'uuid';
    public $incrementing = false;
<<<<<<< HEAD

=======
    protected $appends = ['image'];
    const COMPANY = 2;
    const INDIVIDUAL = 1;
>>>>>>> 278d07bbeded43eed01bbcb0228afc9d136270ea

    protected $fillable = [
        'name',
        'email',
<<<<<<< HEAD
        'date_of_birth',
        'gender',
=======
>>>>>>> 278d07bbeded43eed01bbcb0228afc9d136270ea
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


<<<<<<< HEAD
=======
    const PATH_IMAGE = "/upload/user/personal/";

    public function imageUser()
    {
        return $this->morphOne(Upload::class, 'imageable')->where('type', '=', Upload::IMAGE);
    }

    public function fcm_tokens()
    {
        return $this->hasMany(FcmToken::class, 'user_uuid');
    }

    public function getImageAttribute()
    {
        if (@$this->imageUser->filename) {
            return !is_null(@$this->imageUser->path) ? @$this->imageUser->path : '';
        } else {
            return url('/') . '/dashboard/app-assets/images/4367.jpg';
        }
    }

>>>>>>> 278d07bbeded43eed01bbcb0228afc9d136270ea

    public static function boot()
    {
        parent::boot();
        self::creating(function ($item) {
            $item->uuid = Str::uuid();
        });
<<<<<<< HEAD
=======
//        static::addGlobalScope('user', function (Builder $builder) {
//            $builder->where('status', 1);//1==active
//        });
>>>>>>> 278d07bbeded43eed01bbcb0228afc9d136270ea

    }
}
