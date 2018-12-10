<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($user){
           $user->activation_token = str_random(30);
        });
    }

    /**头像
     * @param string $size
     * @return string
     */
    public function gravatar($size='100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    /**发送密码确认
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**与微博一对多关系(一个用户拥有多条微博)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statuses()
    {
        //Status::class相当于 App/Models/Status
        return $this->hasMany(Status::class);
    }

    //获取到多条微博
    public function feed()
    {
        //获取到id的数组
        $user_ids = \Auth::user()->followings->pluck('id')->toArray();
        //将当前id插入数组
        array_push($user_ids, \Auth::user()->id);
        //查询出微博
        return Status::whereIn('user_id', $user_ids)
            ->with('user')
            ->orderBy('created_at', 'desc');
    }

    /**获取粉丝表  多对多
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function follower()
    {
        return $this->belongsToMany(User::class,'followers','user_id','follower_id');
    }

    /**获取关注人表 多对多
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followings()
    {
        /**四个参数意思：
         * 1、目标model的class全称呼。
         * 2、中间表名
         * 3、中间表中当前model对应的关联字段
         * 4、中间表中目标model对应的关联字段
         */
        return $this->belongsToMany(User::class,'followers','follower_id','user_id');
    }

    /**关注
     * @param $user_ids
     */
    public function follow($user_ids)
    {
        if (!is_array($user_ids)) {
            //如果$user_ids不是一个数组 则用compact转化成数组
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids, false);
    }

    /**取消关注
     * @param $user_ids
     */
    public function unfollow($user_ids)
    {
        if (!is_array($user_ids)){
            //如果$user_ids不是一个数组 则用compact转化成数组
            $user_ids = compact('user_ids');
        }
        //detach方法将会从中间表中移除相应的记录；然而，两个模型在数据库中都保持不变
        $this->followings()->detach($user_ids);
    }

    /**是否关注
     * @param $user_id
     * @return mixed
     */
    public function isfollowing($user_id)
    {
        //contains 方法判断集合是否包含一个给定项
        return $this->followings->contains($user_id);
    }
}
