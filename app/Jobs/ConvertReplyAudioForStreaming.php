<?php

namespace App\Jobs;

use Carbon\Carbon;
use FFMpeg;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;
use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ConvertReplyAudioForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $reply;
    public $audio;

    public $timeout = 1800;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Reply $reply, $audio)
    {
        $this->reply = $reply;
        $this->audio = $audio;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $audio_path = Reply::$audio_directory . uniqid() . '.mp3';

        FFMpeg::fromDisk('public')
          ->open($this->audio)
          ->export()
          ->toDisk('digitalocean')
          ->inFormat(new \FFMpeg\Format\Audio\Mp3)
          ->save($audio_path);

        $this->reply->update([
          'audio' => Storage::cloud()->url($audio_path)
        ]);

         // Storage::disk($this->video->disk)->delete($this->video->video_path);
         // FFMpeg::cleanupTemporaryFiles();
    }
}
