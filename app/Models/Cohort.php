<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Cohort extends Model
{
    use HasFactory;

    protected $fillable = [
      'course_id',
      'enable_chatroom',
      'title',
      'active',
      'companion'
    ];

    protected $casts = [
      'course_id' => 'integer',
      'enable_chatroom' => 'boolean',
      'active' => 'boolean',
      'companion' => 'boolean'
    ];

    protected $appends = [
    'hash'
    ]; 

    protected $with = ['course'];

    public function getHashAttribute() {
      return \Hashids::encode($this->id);
    }

    public function scopeActive($query) {
      return $query->where('active', true);
    }

    public function scopeInactive($query) {
      return $query->where('active', false);
    }

    public function scopeCompanion($query) {
        return $query->where('companion')->where('active');
    }

  public function course()
  
  {
    return $this->belongsTo('App\Models\Course');
  }

    public function users()
  {
    return $this->belongsToMany('App\Models\User', 'user_cohorts')->withPivot('is_subscription_based');;
  }
}
