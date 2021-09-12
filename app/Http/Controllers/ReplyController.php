<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Reply;
use App\Models\Lesson;
use App\Models\Course;
use App\Models\Cohort;
use App\Models\Week;
use App\Models\Section;
use App\Models\Video;

use App\Http\Requests\StoreReply;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ConvertReplyVideoForStreaming;
use App\Jobs\ConvertReplyAudioForStreaming;

class ReplyController extends Controller
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

    public function destroy(Request $request, Cohort $cohort, Course $course, Week $week, Lesson $lesson, Section $section, Reply $reply) {
      $reply->delete();

      return redirect()->route($request->in_chatroom_manager ? 'chatroom.section' : 'section.show', [
        'cohort' => $cohort,
        'course' => $course,
        'week' => $week,
        'lesson' => $lesson,
        'section' => $section
      ]);
    }

    /**
     * Create a new reply
     *
     * @return \Illuminate\Http\Response
     */

    public function store(StoreReply $request, Cohort $cohort, Lesson $lesson, Section $section) {

      if($request->video) {
        $video = Video::create([
          'disk'              => 'public',
          'unprocessed_path'  => $request->video->store(Video::$unprocessed_directory, 'public'),
        ]);

        $this->dispatch(new ConvertReplyVideoForStreaming($video));
      }


      $reply = Reply::create([
        'cohort_id' => $cohort->id,
        'user_id' => $request->user_id,
        'reply_id' => $request->reply_id,
        'lesson_id' => $request->reply_id ? null : $lesson->id,
        'section_id' => $request->reply_id ? null : $section->id,
        'video_id' => $request->video ? $video->id : null,
        'text' => $request->text,
        'type' => $request->audio ? 'audio' :
                    ($request->video ? 'video' : 'text')
      ]);


      if($request->audio) {
        $unprocessed_audio = $request->audio->store(Reply::$unprocessed_audio_directory, 'public');
        $this->dispatch(new ConvertReplyAudioForStreaming($reply, $unprocessed_audio));
      }

      return redirect()->back();

    }
}
