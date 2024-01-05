<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;

use Auth;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasFactory, MustVerifyEmailTrait;

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

    public function notify($instance){
         echo  "666";
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
    
}