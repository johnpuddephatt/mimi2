<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

use App\Models\Comment;
use App\Models\Reply;

class ActivityController extends Controller
{
  public function index()
  {
    $activities = Activity::orderBy('id','DESC')
      ->with(['subject' => function (MorphTo $morphTo) {
        $morphTo->morphWith([
            Comment::class => ['reply.user:id','reply.lesson.week.course:id'],
            Reply::class => ['reply.lesson.week.course:id','lesson.week.course:id'],
        ]);
      }])
      ->with('causer:id,first_name,photo')
      ->paginate(10);

    // ->with('causer:id,first_name,photo','subject.user:id,first_name,photo', 'subject.lesson:id')->simplePaginate(12);
    return view('activity', compact('activities'));
  }
}
