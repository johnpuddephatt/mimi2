<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Course;
use App\Models\Lesson;

class ChatroomController extends Controller
{

  public function index() {
    return redirect()->route('chatroom.course', ['course' => Course::first()]);
  }

  public function course(Course $course) {

    $lessons = $course->lessons()->whereHas('sections', function ($query) {
      $query->where('is_chatroom', '=', true);
    })->get();

    if($lessons->count()) {
      return redirect()->route('chatroom.lesson', ['course' => $course, 'lesson' => $lessons->first()]);
    }

    return Inertia::render('Admin/Chatroom/Course', [
      'courses' => Course::all(),
      'course' => $course
    ]);

  }

  public function lesson(Request $request, Course $course, Lesson $lesson) {

    $lessons = $course->lessons()->whereHas('sections', function ($query) {
      $query->where('is_chatroom', '=', true);
    })->get();

    $already_replied_to = ($request->already_replied_to == 'true');

    return Inertia::render('Admin/Chatroom/Lesson', [
      'courses' => Course::all(),
      'course' => $course,
      'lesson' => $lesson,
      'lessons' => $lessons,
      'include_already_replied_to' => $already_replied_to,
      'replies' => $already_replied_to ?
        fn () => $lesson->replies()->with('user:id,first_name,last_name,description,photo,email,created_at','video', 'feedback.video')->get() :
          fn () => $lesson->replies()->feedbackless()->with('user:id,first_name,last_name,description,photo,email,created_at','video', 'feedback.video')->get()

    ]);
  }
}
