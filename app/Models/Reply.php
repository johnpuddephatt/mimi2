<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

use Mail;
use App\Mail\NewReply;
use App\Mail\NewFeedback;

use Spatie\Activitylog\Traits\LogsActivity;

class Reply extends Model
{
    use LogsActivity;

    public static $audio_directory = "replies/audio/";
    public static $unprocessed_audio_directory = "audio/unprocessed";


    protected $fillable = [
        'video_id',
        'cohort_id',
        'audio',
        'text',
        'lesson_id',
        'section_id',
        'reply_id',
        'user_id',
        'type'
    ];

    protected $casts = [
      'user_id' => 'integer',
      'cohort_id' => 'integer',
      'lesson_id' => 'integer',
      'section_id' => 'integer',
      'reply_id' => 'integer',
      'video_id' => 'integer',
    ];

    // protected $with = ['user'];

    protected $withCount = ['comments'];

    protected static function boot() {
      parent::boot();

      static::addGlobalScope('order', function (Builder $builder) {
        $builder->orderBy('id', 'desc');
      });

      static::created(function($reply){

        if($reply->reply_id) {
          $reply->sendFeedbackNotification();
        }

        // Notify admins
        // else {
        //   $reply->sendReplyNotification();
        // }

      });
    }

    public function scopeAudioOrVideo($query){
      return $query->where('type', 'video')->orWhere('type', 'audio');
    }

    public function scopeText($query){
      return $query->where('type', 'text');
    }

    public function scopeFeedbackless($query) {
      return $query->doesntHave('feedback');
    }

    public function user()
    {
      return $this->belongsTo('App\Models\User');
    }

    public function lesson()
    {
      return $this->belongsTo('App\Models\Lesson');
    }

    public function feedback()
    {
      return $this->hasOne('App\Models\Reply');
    }

    public function reply()
    {
      return $this->belongsTo('App\Models\Reply');
    }

    public function comments()
    {
      return $this->hasMany('App\Models\Comment');
    }

    public function parent_comments()
    {
      return $this->hasMany('App\Models\Comment')->doesntHave('parentComment');
    }

    public function video()
    {
      return $this->belongsTo('App\Models\Video');
    }

    // A top-level video comment from a student
    // public function sendReplyNotification() {
    //   $email = new NewReply($this);
    //   $recipient = User::where('is_admin', true)->get();
    //   Mail::to($recipient)->send($email);
    // }

    // Feedback video from an admin
    public function sendFeedbackNotification() {
      $recipient = $this->reply->user;
      if($recipient->receives('FeedbackReceived')) {
        $email = new NewFeedback($this);
        Mail::to($recipient)->send($email);
      }
    }

}
