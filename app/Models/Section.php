<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Builder;
use \EditorJS\EditorJS;
use Illuminate\Support\Facades\Cache;

class Section extends Model implements Sortable
{
  use SortableTrait;
  use HasFactory;

  public static $uploadPaths = [
    'image' => 'editorjs/image/',
    'audio' => 'editorjs/audio/',
    'video_temporary' => 'public/editorjs/unprocessed_video/',
    'video_playlist' => 'editorjs/video/'
  ];

  protected $fillable = [
      'title',
      'content',
      'lesson_id',
      'is_chatroom',
      'order'
  ];

  protected $casts = [
    'lesson_id' => 'integer',
    'is_chatroom' => 'boolean',
    'order' => 'integer'
  ];

  public $sortable = [
    'order_column_name' => 'order',
    'sort_when_creating' => true,
  ];

  protected static function boot() {
    parent::boot();
    static::addGlobalScope('order', function (Builder $builder) {
      $builder->orderBy('order', 'asc');
    });
  }

  protected static function booted()
    {
      static::updated(function ($section) {
        Cache::forever('section_' . $section->id, view('editorjs', ['blocks' => $section->getBlocks()])->render());
      });
    }

  public function buildSortQuery()
  {
    return static::query()->where('lesson_id', $this->week_id);
  }

  public function getBlocks() {
    try {
      $editor = new EditorJS( $this->content, json_encode(config('editorjs')) );
      return $editor->getBlocks();
    }
    catch (\EditorJSException $e) {
      report($e);
      return false;
    }
  }

  public function week()
  {
    return $this->belongsTo(\App\Models\Week::class);
  }
}
