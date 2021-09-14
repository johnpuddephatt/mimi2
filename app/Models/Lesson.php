<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'instructions',
        'day',
        'week_id',
        'live'
    ];

    protected $casts = [
      'week_id' => 'integer',
      'day' => 'integer',
      'live' => 'boolean'
    ];

    protected static function booted() {
      static::addGlobalScope('order', function (Builder $builder) {
        $builder->orderBy('day', 'asc');
      });
    }

    public function scopeLive($query){
      return $query->where('live', true);
    }

    public function next() {
      return $this->week->lessons()->select('id','title')->where('day', '>', $this->day)->live()->first();
    }

    public function is_last() {
      return $this->id == $this->week->lessons->last()->id;
    }

    public function week()
    {
      return $this->belongsTo(\App\Models\Week::class);
    }

    public function sections()
    {
      return $this->hasMany(\App\Models\Section::class)->ordered();
    }

    public function replies()
    {
      return $this->hasMany(\App\Models\Reply::class);
    }

    public function course()
   {
       return $this->hasOneThrough(\App\Models\Course::class, \App\Models\Week::class);
   }
}
