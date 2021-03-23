<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Reply;
use App\Models\Lesson;
use App\Models\Video;

use App\Http\Requests\StoreReply;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ConvertReplyVideoForStreaming;

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

    public function destroy(Lesson $lesson, Reply $reply) {
      $reply->delete();
    }

    /**
     * Create a new reply
     *
     * @return \Illuminate\Http\Response
     */

    public function store(StoreReply $request, Lesson $lesson) {

      $video = Video::create([
        'disk'              => 'public',
        'unprocessed_path'  => $request->video->store(Video::$unprocessed_directory, 'public'),
      ]);

      $this->dispatch(new ConvertReplyVideoForStreaming($video));

      Reply::create([
          'user_id' => $request->user_id,
          'reply_id' => null,
          'lesson_id' => $request->reply_id ? null : $lesson->id,
          'video_id' => $video->id,
      ]);

      return redirect()->back();

    }

}
