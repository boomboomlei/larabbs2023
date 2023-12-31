<?php

namespace App\Observers;

use App\Models\Reply;

use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        $reply->content=clean($reply->content,'user_reply_body');
    }

    public function updating(Reply $reply)
    {
        //
    }

    public  function created(Reply $reply){
        // $reply->topic->increment('reply_count',1);
        // $reply->topic->reply_count = $reply->topic->replies->count();
        // $reply->topic->save();

        if(! app()->runningInConsole()){
            $reply->topic->updateReplyCount();
            $reply->topic->user->TopicNotify(new TopicReplied($reply));
        }
    }

    public function deleted(Reply $reply){
        $reply->topic->updateReplyCount();
    }

    
}