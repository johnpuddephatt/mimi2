<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Cohort;
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

    public function edit(Course $course) {
      $course->load('weeks.lessons');
      $course->load('cohorts');
      return Inertia::render('Admin/Course/Form', ['data' => $course ]);
    }

    public function update(Course $course, StoreCourse $request) {
      $course->update($request->all());
      return redirect()->route('course.edit', ['course' => $course]);
    }
    
    public function users(Course $course) {
      return $course->users;
    }

    public function map(Cohort $cohort, Course $course) {
      // Must have foreign key for load to only select specific columns (i.e. lesson_id in this case)
      $course->load('weeks.lessons.sections:id,title,lesson_id');
      return $course;
    }

    public function index()
    {
      if(\Auth::User()->is_admin) {
        $courses = Course::all();
      }
      else {
        $courses = \Auth::User()->courses;
        $courses = $courses->merge(Course::open()->get());
      }
      return Inertia::render('Course/Index', compact('courses'));


    }
}
