<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Video extends Model
{
    public static $unprocessed_directory = "videos/unprocessed";
    public static $video_directory = "videos/data/";
    public static $thumbnail_directory = "videos/thumbnail/";

    protected $fillable = [
        'disk',
        'unprocessed_path',
        'playlist_path',
        'thumbnail_path',
        'converted_for_streaming_at'
    ];

    protected $dates = [
       'converted_for_streaming_at',
    ];

    // dubious??
    public function video()
    {
      return $this->hasOne('App\Models\Lesson');
    }

    public function reply()
    {
      return $this->belongsTo('App\Models\Reply');
    }

    public function comment()
    {
      return $this->hasOne('App\Models\Comment');
    }

    protected static function boot() {
        parent::boot();
        static::addGlobalScope('processed', function (Builder $builder) {
            $builder->whereNotNull('converted_for_streaming_at');
        });
   }

   public function getTimeToConvertAttribute() {
      return Carbon::parse($this->created_at)->diffInSeconds(Carbon::parse($this->converted_for_streaming_at));
   }
}
