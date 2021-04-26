<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
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

    public function show(Request $request, Course $course, Week $week, Lesson $lesson, Section $section, Reply $reply = null) {

        if(!$lesson->live && !\Auth::user()->is_admin) {
          return back()->with('message', 'This lesson is not live yet.');
        }

        return Inertia::render('Section/Show', [
          'course' => $course->only('id','title','archived'),
          'week' => $week->only('id','name','number'),
          'lesson' => $lesson->load('sections:id,title,lesson_id')->only('id','title','instructions','day','sections'),
          'section' => $section->only('id','title','order','is_chatroom'),
          'replies' => $section->is_chatroom ? fn () => $lesson->replies()->with('video')->get() : null,
          'comments' => $reply ? $reply->parent_comments : null,
          'blocks_prerendered' => Cache::rememberForever('section_' . $section->id, function() use($section) {
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
