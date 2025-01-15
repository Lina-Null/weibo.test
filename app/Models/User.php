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

    //将当前用户发布过的所有微博从数据库中取出，并根据创建时间来倒序排序
    public function feed()
    {
        return $this->statuses()
                    ->orderBy('created_at', 'desc');
    }

    public function followers()
    {
        //1个用户可以有多个粉丝
        //belongsToMany 方法的第三个参数 user_id 是定义在关联中的模型外键名，而第四个参数 follower_id 则是要合并的模型外键名。
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    public function follow($user_ids)
    {
        //用于判断参数是否为数组，如果已经是数组，则没有必要再使用 compact 方法
        if ( ! is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        //sync 会自动获取数组中的 id
        $this->followings()->sync($user_ids, false);
    }

    public function unfollow($user_ids)
    {
        //用于判断参数是否为数组，如果已经是数组，则没有必要再使用 compact 方法
        if ( ! is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        //detach 会自动获取数组中的 id
        $this->followings()->detach($user_ids);
    }

    //判断当前登录的用户 A 是否关注了用户 B
    //只需判断用户 B 是否包含在用户 A 的关注人列表上即可
    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }
}
