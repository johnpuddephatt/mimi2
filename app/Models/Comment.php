<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Mail;
use App\Mail\NewComment;
use Spatie\Activitylog\Traits\LogsActivity;

class Comment extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['value'];

    protected $fillable = [
      'reply_id',
      'user_id',
      'value',
      'comment_id'
    ];

    protected $casts = [
      'user_id' => 'integer',
      'reply_id' => 'integer',
      'comment_id' => 'integer',
    ];

    protected $with = ['user', 'comments'];

    protected static function boot() {
      parent::boot();

      static::created(function($comment){
        $comment->sendCommentNotification();
      });
    }

    public function getValueAttribute($value)
    {
        return str_replace('<a ','<a target="_blank"', \Markdown::parse($value)->toHtml());
    }

    public function user() {
      return $this->belongsTo('App\Models\User');
    }

    public function reply() {
      return $this->belongsTo('App\Models\Reply');
    }

    public function comments()
    {
      return $this->hasMany('App\Models\Comment');
    }

    public function parentComment()
    {
      return $this->belongsTo('App\Models\Comment', 'comment_id');
    }

    public function sendCommentNotification() {

      if($this->user->id != $this->reply->user->id) {
        if(($this->user->is_admin && $this->reply->user->receives('TeacherCommentReceived'))
          || (!$this->user->is_admin && $this->reply->user->receives('StudentCommentReceived'))) {
            $email = new NewComment($this);
            $recipient = $this->reply->user;
            Mail::to($recipient)->send($email);
        }
      }
      //
      // elseif($this->parentComment && ($this->parentComment->user->id != $this->user->id) && $this->parentComment->user->receives('ChildCommentReceived')) {
      //   $email = new NewChildComment($this);
      //   $recipient = $this->parentComment->user;
      //   Mail::to($recipient)->send($email);
      // }
    }

}
