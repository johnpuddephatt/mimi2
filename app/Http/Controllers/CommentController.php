<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\StoreComment;
use Illuminate\Support\Facades\Storage;

use App\Models\Course;
use App\Models\Comment;
use App\Models\Reply;
use App\Models\Lesson;
use App\Models\Week;
use App\Models\Section;

class CommentController extends Controller
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

    public function delete(Request $request, Course $course, Comment $comment) {
      $comment->delete();
      return redirect()->back();
    }

    public function store(StoreComment $request, Course $course) {
      Comment::create($request->all());
      return redirect()->back();
    }

    public function index(Lesson $lesson, Reply $reply) {
      return $reply->parent_comments;
    }
}
