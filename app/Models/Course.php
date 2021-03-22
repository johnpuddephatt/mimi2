<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{

  use HasFactory;

  protected $fillable = [
      'title', 'description', 'archived'
  ];

  protected $casts = [
    'archived' => 'boolean'
  ];

  public function hash() {
    return \Hashids::encode($this->id);
  }

  public function feedbackless_reply_count() {
    return $this->replies()->feedbackless()->count();
  }

  public function users()
  {
    return $this->belongsToMany('App\Models\User', 'enrolments');
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
