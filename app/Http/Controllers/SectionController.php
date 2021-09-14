<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Cohort;
use App\Models\Course;
use App\Models\Reply;
use App\Models\Week;
use App\Models\Lesson;
use App\Models\Section;
use Redirect;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreSection;

use Illuminate\Support\Facades\Route;

class SectionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function redirect(Request $request, Course $course, Week $week, Lesson $lesson, Section $section) {
      if(\Auth::user()) {
        $cohort = \Auth::user()->cohorts()->where('course_id', $course->id)->latest()->first();
        if($cohort) {
          return redirect()->route('section.show', ['cohort' => $cohort, 'course' => $course, 'week' => $week, 'lesson' => $lesson, 'section' => $section]) ;
        }
        else {
          abort(404);
        }
      }
      else {
        return abort(404);
      }
    }

    public function show(Request $request, Cohort $cohort, Course $course, Week $week, Lesson $lesson, Section $section, Reply $reply = null) {

        if(!$lesson->live && !\Auth::user()->is_admin) {
          return back()->with('message', 'This lesson is not live yet.');
        }

        $lesson_with_sections = $lesson->load('sections:id,title,lesson_id')->only('id','title','instructions','day','sections');
        return Inertia::render('Section/Show', [
          'enable_chatroom' => $cohort->enable_chatroom,
          'allow_new' => $cohort->active,
          'course' => fn () => $course->only('id','title'),
          'week' => fn () => $week->only('id','name','number'),
          'lesson' => fn () => $lesson_with_sections,
          'section' => fn () => $section->only('id','title','order','is_chatroom'),
          'replies' => $section->is_chatroom ? fn () => $lesson->replies()->where('cohort_id',$cohort->id)->with('user:id,first_name,last_name,description,photo,email,created_at','video', 'feedback.video')->get() : null,
          'next_lesson' => fn () => ($section->is_last() && !$lesson->is_last()) ? $lesson->next() : null,
          'next_week' => fn () => ($section->is_last() && $lesson->is_last() && !$week->is_last()) ? $week->next() : null,
          'end_of_course' => fn () => ($section->is_last() && $lesson->is_last() && $week->is_last()) ? true : false,
          'comments' => $reply ? fn () => $reply->parent_comments : null,
          'blocks_prerendered' => fn () => Cache::rememberForever('section_' . $section->id, function() use($section) {
            return view('editorjs', ['blocks' => $section->getBlocks()])->render();
          })
        ]);
    }


    public function store(StoreSection $request, Course $course, Week $week, Lesson $lesson) {
      $section = Section::create([
          'title' => $request->title,
          'content' => $request->content,
          'is_chatroom' => $request->is_chatroom,
          'lesson_id' => $lesson->id,
          'order' => $lesson->sections->count() ? ($lesson->sections->sortByDesc('order')->first()->order + 1) : 0
      ]);

      return Redirect::route('section.edit', [
            'course' => $course,
            'week' => $week,
            'lesson' => $lesson,
            'section' => $section
        ]);
    }

    public function update(Request $request, Course $course, Week $week, Lesson $lesson, Section $section) {
       $section->update([
        'title' => $request->title,
        'content' => $request->content,
        'is_chatroom' => $request->is_chatroom
      ]);
      return Inertia::render('Admin/Section/Form', ['data' => $section ]);
    }

    public function create(Course $course, Week $week, Lesson $lesson) {
      return Inertia::render('Admin/Section/Form');
    }

    public function edit(Request $request, Course $course, Week $week, Lesson $lesson, Section $section) {
      return Inertia::render('Admin/Section/Form', ['data' => $section ]);
    }

    public function delete(Course $course, Week $week, Lesson $lesson, Section $section) {
      $section->delete();
      return Redirect::back();

    }


}
