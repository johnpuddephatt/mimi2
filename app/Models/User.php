<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Cashier\Billable;
use Illuminate\Support\Facades\Storage;
use App\Traits\SubscriptionsSync;

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
        'first_name', 'last_name', 'photo', 'email', 'password', 'description', 'is_admin'
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
        'trial_ends_at' => 'datetime'
    ];


    public function hasCredits() {
      return $this->credits > 0;
    }

    public function courses()
    {
      return $this->belongsToMany('App\Models\Course', 'enrolments')->withPivot('is_subscription_based');
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
