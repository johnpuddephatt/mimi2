<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\User;
use App\Http\Requests\StoreCourse;
use App\Providers\RouteServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class CourseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function manage() {
      $courses = Course::orderBy('id', 'desc')->get();
      return Inertia::render('Admin/Courses', compact('courses'));
    }

    public function create() {
      return Inertia::render('Admin/Course/Form');
    }

    public function store(StoreCourse $request) {
      $course = Course::create($request->all());
      return redirect()->route('course.edit', ['course' => $course]);
    }

    public function show(Course $course) {
      $course->load('weeks');
      return Inertia::render('Course/Show', ['course' => $course]);
    }

    public function edit(Course $course) {
      $course->load('weeks.lessons');
      return Inertia::render('Admin/Course/Form', ['data' => $course ]);
    }

    public function update(Course $course, StoreCourse $request) {
      $course->update($request->all());
      return redirect()->route('course.edit', ['course' => $course]);
    }

    public function enrollCurrentUser($course) {
      if (\Auth::guard()->check()) {

        if(\Auth::User()->courses->contains(\Hashids::decode($course)[0])) {
          return redirect(RouteServiceProvider::HOME)->with('message', 'You are already enrolled in this course');
        }
        else {
          \Auth::User()->courses()->attach(\Hashids::decode($course));
          return redirect(RouteServiceProvider::HOME)->with('message', 'You have been enrolled.');
        }
      }
      else {
        return view('enrol', compact('course'));
      }
    }

    public function enroll(Request $request, Course $course) {
      return $course->users()->attach($request->all());
    }

    public function unenroll(Course $course, User $user) {
      $user->courses()->detach($course->id);
      return back();
    }

    public function users(Course $course) {
      return $course->users;
    }

    public function map(Course $course) {
      // Must have foreign key for load to only select specific columns (i.e. lesson_id in this case)
      $course->load(  'weeks.lessons.sections:id,title,lesson_id');
      return $course;
    }

    public function index()
    {
      if(\Auth::User()->is_admin) {
        $courses = Course::all();
        return view('courses', compact('courses'));
      }
      else {
        $courses = \Auth::User()->courses;
        if($courses->count() == 1) {
          return redirect()->route('course.single', ['course' => $courses->first()]);
        }
        else {
          return view('courses', compact('courses'));
        }
      }
    }
}
