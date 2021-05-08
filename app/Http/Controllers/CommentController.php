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

    public function delete(Request $request, Course $course, Week $week, Lesson $lesson, Section $section, Reply $reply, Comment $comment) {
      $comment->delete();

      return redirect()->back();

      // return redirect()->route($request->in_chatroom_manager ? 'chatroom.reply' : 'section.reply', [
      //   'course' => $course,
      //   'week' => $week,
      //   'lesson' => $lesson,
      //   'section' => $section,
      //   'reply' => $reply
      // ]);
    }

    public function store(StoreComment $request, Course $course, Week $week, Lesson $lesson, Section $section, Reply $reply) {
      Comment::create($request->all());
      return redirect()->back();

      // return redirect()->route($request->in_chatroom_manager ? 'chatroom.reply' : 'section.reply', [
      //   'course' => $course,
      //   'week' => $week,
      //   'lesson' => $lesson,
      //   'section' => $section,
      //   'reply' => $reply,
      //   'include_already_replied_to' => $request->include_already_replied_to
      // ]);
    }

    public function index(Lesson $lesson, Reply $reply) {
      return $reply->parent_comments;
    }
}
