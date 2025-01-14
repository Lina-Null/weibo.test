<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     //头像
     public function gravatar($size = '100')
     {
         $hash = md5(strtolower(trim($this->attributes['email'])));
         return "https://cdn.v2ex.com/gravatar/$hash?s=$size";
     }

     //boot 方法会在用户模型类完成初始化之后进行加载
     public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->activation_token = Str::random(10);
        });
    }

    //需要注意的一点是，由于一个用户拥有多条微博，因此在用户模型中我们使用了微博动态的复数形式 statuses 来作为定义的函数名
    public function statuses(){
        //在用户模型中，指明一个用户拥有多条微博。
        return $this->hasMany(Status::class);
    }
}
