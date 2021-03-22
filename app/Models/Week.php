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
    ];

    protected $casts = [
      'course_id' => 'integer',
      'number' => 'integer'
    ];

    protected static function boot() {
      parent::boot();
      static::addGlobalScope('order', function (Builder $builder) {
        $builder->orderBy('number', 'asc');
      });
    }

    public function course()
    {
      return $this->belongsTo(\App\Models\Course::class);
    }

    public function lessons()
    {
      return $this->hasMany(\App\Models\Lesson::class);
    }
}
