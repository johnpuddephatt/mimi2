<?php

namespace App\Jobs;

use App\Models\Video;
use Carbon\Carbon;
use FFMpeg;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ConvertReplyVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;

    public $timeout = 1800;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $thumbnail_path = Video::$thumbnail_directory . $this->video->id . '.jpg';
        $playlist_path = Video::$video_directory . $this->video->id . '/playlist.m3u8';

        $lowBitrateFormat  = (new X264('aac','libx264'))->setKiloBitrate(250)->setAdditionalParameters(
          ["-preset", "ultrafast"]
        );

        $highBitrateFormat  = (new X264('aac','libx264'))->setKiloBitrate(1000)->setAdditionalParameters(
          ["-preset", "medium"]
        );



        FFMpeg::fromDisk($this->video->disk)
          ->open($this->video->unprocessed_path)
          // ->addLegacyFilter('-vf', "crop='min(iw,ih)':'min(iw,ih)',scale=480:480")
          ->exportForHLS()
          ->toDisk('digitalocean')
          ->addFormat($lowBitrateFormat, function($media){
            $media->addFilter(function ($filters, $in, $out) {
                $filters->custom($in, "crop='min(iw,ih)':'min(iw,ih)',scale=360:360,fps=20", $out); // $in, $parameters, $out
            });
          })
          ->addFormat($highBitrateFormat, function($media){
            $media->addFilter(function ($filters, $in, $out) {
              $filters->custom($in, "crop='min(iw,ih)':'min(iw,ih)',scale=480:480,fps=20", $out); // $in, $parameters, $out
            });
          })
          ->save($playlist_path)

          ->getFrameFromSeconds(0)
          ->export()
          ->toDisk('digitalocean')
          ->save($thumbnail_path);

        Storage::disk($this->video->disk)->delete($this->video->unprocessed_path);
        FFMpeg::cleanupTemporaryFiles();

         $this->video->update([
             'converted_for_streaming_at' => Carbon::now(),
             'thumbnail_path' => Storage::cloud()->url($thumbnail_path),
             'playlist_path' => Storage::cloud()->url($playlist_path),
             'unprocessed_path' => null
         ]);
    }
}
