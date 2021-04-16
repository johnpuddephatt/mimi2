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
          if(\Auth::User()->subscribed() || \Auth::User()->hasCredits()) {
            \Auth::User()->courses()->attach(\Hashids::decode($course), [
              'is_subscription_based' => (\Auth::User()->hasCredits() ? false : true)
            ]);
            if(\Auth::User()->hasCredits()) {
              \Auth::User()->decrement('credits');
            }
            return redirect(RouteServiceProvider::HOME)->with('message', 'You have been enrolled.');
          }
          else {
            return redirect(RouteServiceProvider::HOME)->with('message', 'You do not have a active payment plan.');
          }

        }
      }
      // The below allows unregistered users to create an account to enrol.
      else {
        return view('enrol', compact('course'));
      }

      // else {
      //   return redirect()->route('login', ['course' => $course ]);
      // }
    }

    public function enroll(Request $request, Course $course) {
      $user_ids = $request->all();

      foreach($user_ids as $user_id) {
        $user = User::find($user_id);
        if(!$user->courses->contains($course)) {
          $user->courses()->attach($course, [
            'is_subscription_based' => ($user->hasCredits() ? false : true)
          ]);
          if($user->hasCredits()) {
            $user->decrement('credits');
          }
        }
      }
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
        $courses = $courses->merge(Course::open()->get());
        return view('courses', compact('courses'));
      }
    }
}
