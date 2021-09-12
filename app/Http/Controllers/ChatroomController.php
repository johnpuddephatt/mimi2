<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Course;
use App\Models\Cohort;
use App\Models\Lesson;
use App\Models\Week;
use App\Models\Section;
use App\Models\Reply;

class ChatroomController extends Controller
{
    public function index()
    {
        return redirect()->route('chatroom.cohort', ['cohort' => Cohort::first(), 'course' => Cohort::first()->course_id ]);
    }

    public function cohort(Cohort $cohort, Course $course)
    {
        $lessons = $cohort->course->lessons()->whereHas('sections', function ($query) {
            $query->where('is_chatroom', '=', true);
        })->with('week:id,name')->get();

        if ($lessons->count()) {
            return redirect()->route('chatroom.lesson', ['cohort' => $cohort, 'course' => $course, 'lesson' => $lessons->first()]);
        }

        return Inertia::render('Admin/Chatroom/Cohort', [
          'new_count' => Reply::whereNull('reply_id')->feedbackless()->count(),
          'cohorts' => Cohort::all(),
          'cohort' => $cohort,
          'course' => $course
        ]);
    }

    public function lesson(Request $request, Cohort $cohort, Course $course, Lesson $lesson)
    {
        $section = $lesson->sections()->where('is_chatroom', true)->first();

        if ($section) {
            return redirect()->route('chatroom.section', ['cohort' => $cohort, 'course' => $course, 'week' => $lesson->week, 'lesson' => $lesson, 'section' => $section ]);
        } else {
            abort(404);
        }
    }


    public function section(Request $request, Cohort $cohort, Course $course, Week $week, Lesson $lesson, Section $section, Reply $reply = null)
    {
        $lessons = $course->lessons()->whereHas('sections', function ($query) {
            $query->where('is_chatroom', '=', true);
        })->with('week:id,name')->get();

        $lessons->each->append('feedbackless_reply_count');

        $replies = $request->include_already_replied_to ?
                                $lesson->replies()->with('user:id,first_name,last_name,description,photo,email,created_at', 'video', 'feedback.video')->get() : $lesson->replies()->feedbackless()->with('user:id,first_name,last_name,description,photo,email,created_at', 'video', 'feedback.video')->get();

        if ($reply && !$request->include_already_replied_to && !$replies->contains(
            function ($value) use ($reply) {
                return $value->id == $reply->id;
            }
        )) {
            $replies->push($reply->load('user:id,first_name,last_name,description,photo,email,created_at', 'video', 'feedback.video'));
        }

        return Inertia::render('Admin/Chatroom/Section', [
            'new_count' => Reply::whereNull('reply_id')->feedbackless()->count(),
            'cohorts' => fn () => Cohort::all(),
            'cohort' => fn () => $cohort,
            'course' => fn () => $course,
            'lesson' => fn () => $lesson->load('week'),
            'lessons' => fn () => $lessons,
            'include_already_replied_to' => $request->include_already_replied_to,
            'comments' => $reply ? fn () => $reply->parent_comments : null,
            'replies' => fn () => $replies
        ]);
    }
}
