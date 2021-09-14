<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{

  use HasFactory;

  protected $fillable = [
      'title', 'description'
  ];

  protected $casts = [
  ];

  protected $appends = [
    // 'hash'
  ];

  // public function getHashAttribute() {
  //   return \Hashids::encode($this->id);
  // }

  public function users()
  {
    return $this->belongsToMany('App\Models\User', 'enrolments')->withPivot('is_subscription_based');
  }

  public function cohorts()
  {
    return $this->hasMany('App\Models\Cohort');
  }

  public function weeks()
  {
    return $this->hasMany('App\Models\Week');
  }

  public function replies()
  {
    return $this->hasManyThrough('App\Models\Reply', 'App\Models\Lesson');
  }

  public function lessons()
  {
    return $this->hasManyThrough('App\Models\Lesson', 'App\Models\Week');
  }
}
