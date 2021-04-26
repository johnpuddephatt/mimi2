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

    protected static function boot() {
      parent::boot();
      static::addGlobalScope('order', function (Builder $builder) {
        $builder->orderBy('day', 'asc');
      });
    }

    public function feedbackless_reply_count() {
      return $this->replies()->feedbackless()->count();
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
}
