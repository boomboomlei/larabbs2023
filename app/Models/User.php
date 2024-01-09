<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;

use Spatie\Permission\Traits\HasRoles;


use  Illuminate\Support\Str;
use Auth;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasRoles;
    use HasFactory, MustVerifyEmailTrait;

    use Traits\ActiveUserHelper;
    use Traits\LastActivedAtHelper;

    use Notifiable {
        notify as protected laravelNotify;
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        "introduction",
        "avatar"

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
       // 'email_verified_at' => 'datetime',
    ];

    public function topics(){
        return $this->hasMany(Topic::class);
    }

    public function isAuthorOf($model){
        return $this->id== $model->user_id;
    }

    public function replies(){
        return $this->hasMany(Reply::class);
    }

    public function TopicNotify($instance){
        if($this->id == Auth::id() && get_class($instance)!="Illuminate\Auth\Notifications\VerifyEmail"){
            return;
        }

        if(method_exists($instance,'toDatabase')){
            $this->increment('notification_count');
        }
        $this->laravelNotify($instance);
    }

    public function markAsRead(){
        $this->notification_count=0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }
    


    //修改器
    public function setPasswordAttribute($value){
        if(strlen($value) !=60){
            $value=bcrypt($value);
        }
        $this->attributes['password']=$value;
    }

    public function setAvatarAttribute($path){
        if(!Str::startsWith($path,'http')){
            $path=config("app.url")."/uploads/images/avatars/$path";
        }
        $this->attributes["avatar"]=$path;
    }

}