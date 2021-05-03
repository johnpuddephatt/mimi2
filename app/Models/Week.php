<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'course_id',
        'number',
        'live'
    ];

    protected $casts = [
      'course_id' => 'integer',
      'number' => 'integer',
      'live' => 'boolean'
    ];

    protected static function boot() {
      parent::boot();
      static::addGlobalScope('order', function (Builder $builder) {
        $builder->orderBy('number', 'asc');
      });
    }

    public function next() {
      return $this->course->weeks->where('number', '>', $this->number)->first()->only('id','name','number');

    }

    public function course()
    {
      return $this->belongsTo(\App\Models\Course::class);
    }

    public function lessons()
    {
      return $this->hasMany(\App\Models\Lesson::class);
    }

    public function sections()
    {
        return $this->hasManyThrough(\App\Models\Section::class, \App\Models\Lesson::class);
    }
}
