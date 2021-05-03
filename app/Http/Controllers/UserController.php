<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\User;
use App\Http\Requests\StoreUser;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
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

    public function index()
    {
      return User::select('id', 'first_name', 'last_name', 'email')->where('is_admin', false)->orderBy('last_name')->get();
    }

    public function showProfile() {
      return Inertia::render('User/Profile', ['user' => \Auth::user()]);
    }

    protected function updateProfile(StoreUser $request) {

        if($request->photo) {
          if($request->photo == \Auth::user()->photo) {
            $photo_url = $request->photo;
          }
          else {
            $resized = \Image::make($request->photo)->orientate()->fit(480,480)->encode('jpg',80);
            $photo_path = User::$photo_directory . $request->photo->hashName();
            Storage::cloud()->put($photo_path, $resized);
            $photo_url = Storage::cloud()->url($photo_path);
          }
        }

        \Auth::user()->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'photo' => isset($photo_url) ? $photo_url : null,
            'description' => $request->description,
        ]);

        return Inertia::render('User/Profile', ['user' => \Auth::user()]);
    }

    public function chatrooms() {
      $courses = \Auth::User()->courses;
      $courses = $courses->merge(Course::open()->get());

      return redirect()->route('user.chatroom.course', [
        'course' => $courses->first()
      ]);

    }

    public function chatroomCourse(Course $course) {

      $courses = \Auth::User()->courses;
      $courses = $courses->merge(Course::open()->get());

      $lessons = $course->lessons()->whereHas('sections', function ($query) {
        $query->where('is_chatroom', '=', true);
      })->with('week:id,number')->get();


      $lessons->load(['sections' => function ($query) {
        $query->where('is_chatroom', '=', true)->select(['lesson_id','id']);
      }]);


      $replies = \Auth::User()->replies()->select(['id','lesson_id'])->get()->toArray();

      return Inertia::render('User/Chatrooms', [
        'course' => $course,
        'courses' => $courses,
        'lessons' => $lessons,
        'replies' => $replies
      ]);
    }

}
