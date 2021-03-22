<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Course;
use App\Models\Week;
use Redirect;
use App\Models\Section;
use App\Http\Requests\StoreLesson;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Course $course, Week $week) {
        $latest_lesson_number = ($week->lessons->count() ? $week->lessons()->orderBy('day', 'desc')->first()->day : 0);
        return Inertia::render('Admin/Lesson/Form', ['latest_lesson_number' => $latest_lesson_number ]);
    }

    public function edit(Course $course, Week $week, Lesson $lesson) {
      $lesson->sections = $lesson->sections()->select('id','title')->get();
      return Inertia::render('Admin/Lesson/Form', ['data' => $lesson ]);
    }

    public function store(StoreLesson $request, Course $course, Week $week) {
      $lesson = Lesson::create([
          'title' => $request->title,
          'instructions' => $request->instructions,
          'day' => $request->day,
          'week_id' => $week->id,
      ]);

      return Redirect::route('lesson.edit', [
            'course' => $course,
            'week' => $week,
            'lesson' => $lesson,
        ]);
    }

    public function update(StoreLesson $request, Course $course, Week $week, Lesson $lesson) {
      $lesson->update([
        'title' => $request->title,
        'instructions' => $request->instructions,
        'day' => $request->day,
        'week_id' => $week->id
      ]);
      return Inertia::render('Admin/Lesson/Form', ['data' => $lesson ]);
    }

    // public function show(Course $course, Lesson $lesson, $reply_id = null, $show_feedback = false) {
    //   $lesson->load('replies.feedback');
    //   return view('lesson.single', $reply_id ? compact('lesson', 'reply_id', 'show_feedback') : compact('lesson'));
    // }


    public function show(Course $course, Week $week, Lesson $lesson) {
      $section = $lesson->sections()->first();
      return Redirect::route('section.show', [
        'course' => $course,
        'week' => $week,
        'lesson' => $lesson,
        'section' => 462
      ]);
    }

    public function reorderSections(Course $course, Week $week, Lesson $lesson, $newOrder) {
      Section::setNewOrder(explode(',',$newOrder));
      return Redirect::route('lesson.edit', [
        'course' => $course,
        'week' => $week,
        'lesson' => $lesson
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lesson  $week
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Course $course, Week $week, Lesson $lesson)
    {
      $lesson->delete();

      return Redirect::back();
    }
}
