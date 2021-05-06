<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Week;
use App\Models\Section;
use App\Models\Reply;

class ChatroomController extends Controller
{

  public function index() {
    return redirect()->route('chatroom.course', ['course' => Course::first()]);
  }

  public function course(Course $course) {

    $lessons = $course->lessons()->whereHas('sections', function ($query) {
      $query->where('is_chatroom', '=', true);
    })->with('week:id,name')->get();

    if($lessons->count()) {
      return redirect()->route('chatroom.lesson', ['course' => $course, 'lesson' => $lessons->first()]);
    }

    return Inertia::render('Admin/Chatroom/Course', [
      'courses' => Course::all(),
      'course' => $course
    ]);

  }

  public function lesson(Request $request, Course $course, Lesson $lesson) {

    $section = $lesson->sections()->where('is_chatroom',true)->first();

    if($section) {
      return redirect()->route('chatroom.section', ['course' => $course, 'week' => $lesson->week, 'lesson' => $lesson, 'section' => $section ]);
    }
    else {
      abort(404);
    }
  }


  public function section(Request $request, Course $course, Week $week, Lesson $lesson, Section $section, Reply $reply = null) {

    $lessons = $course->lessons()->whereHas('sections', function ($query) {
      $query->where('is_chatroom', '=', true);
    })->with('week:id,name')->get();

    $lessons->each->append('feedbackless_reply_count');

    return Inertia::render('Admin/Chatroom/Section', [
      'courses' => fn () => Course::all(),
      'course' => fn () => $course,
      'lesson' => fn () => $lesson->load('week'),
      'lessons' => fn () => $lessons,
      'include_already_replied_to' => $request->include_already_replied_to,
      'comments' => $reply ? fn () => $reply->parent_comments : null,
      'replies' => $request->include_already_replied_to ?
        fn () => $lesson->replies()->with('user:id,first_name,last_name,description,photo,email,created_at','video', 'feedback.video')->get() :
          fn () => $lesson->replies()->feedbackless()->with('user:id,first_name,last_name,description,photo,email,created_at','video', 'feedback.video')->get()->push($reply ? $reply->load('user:id,first_name,last_name,description,photo,email,created_at','video', 'feedback.video') : null)

    ]);
  }
}
