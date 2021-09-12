<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Cashier\Billable;
use Illuminate\Support\Facades\Storage;
use App\Traits\SubscriptionsSync;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use Billable;
    use Notifiable;
    use HasFactory;
    use SubscriptionsSync;

    public static $photo_directory = "users/thumbnail/";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'photo', 'email', 'password', 'description', 'is_admin', 'notification_emails'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'email_verified_at' => 'datetime',
      'is_admin' => 'boolean',
      'trial_ends_at' => 'datetime',
      'notification_emails' => AsArrayObject::class
    ];

    public static $notificationEmails = [
      'FeedbackReceived' => 'A teacher leaves feedback on my chatroom reply',
      'StudentCommentReceived' => 'Another student comments on my chatroom reply',
      'TeacherCommentReceived' => 'A teacher comments on my chatroom reply',
      // 'ChildCommentReceived' => 'Someone replies to a comment I’ve left',
    ];

    public function receives($notification) {
      if($this->notification_emails) {
        return in_array($notification, $this->notification_emails->toArray());
      }
    }

    public function getPhotoAttribute($value) {
      if($value) {
        return $value;
      }
      else {
        return 'https://ui-avatars.com/api/?name='.urlencode($this->first_name . ' ' . $this->last_name).'&color=62bfcd&background=EBFFFA';
      }
    }

    public function hasCredits() {
      return $this->credits > 0;
    }

    public function hasActiveCohort() {
      return $this->cohorts()->where('active', true)->where('companion', false)->count();
    }

    public function courses()
    {
      return $this->belongsToMany('App\Models\Course', 'enrolments')->withPivot('is_subscription_based');
    }

    public function cohorts()
    {
      return $this->belongsToMany('App\Models\Cohort', 'user_cohorts')->withPivot('is_subscription_based');
    }

    public function inactiveCohorts()
    {
      return $this->belongsToMany('App\Models\Cohort', 'user_cohorts')->withoutGlobalScopes()->where('active',false)->withPivot('is_subscription_based');
    }

    public function comments()
    {
      return $this->hasMany('App\Models\Comment');
    }

    public function replies()
    {
      return $this->hasMany('App\Models\Reply');
    }
}
