<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reply extends Model
{
    use HasFactory;
    
    protected $fillable = ['content'];

    public  function user(){
         return    $this->belongsTo(User::class);
    }

    public function topic(){
        return $this->belongsTo(Topic::class);
    }

    //test
    // public function scopeWithOrder($query,$order){
    //     switch($order){
    //         case "recent":
    //             $query->recent();
    //             break;
    //         default :
    //             $query->recentReplied();
    //             break;
    //     }
    // }

    // public function scopeRecentReplied($query){
    //     return $query->orderBy("updated_at",'desc');
    // }

    // public function scopeRecent($query){
    //     return $query->orderBy("created_at",'desc');
    // }
}
