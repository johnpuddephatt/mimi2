<?php

namespace App\Jobs;

use App\Models\Section;
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

class ConvertSectionVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $temporary_video_path;
    protected $playlist_path;

    public $timeout = 1800;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($temporary_video_path, $playlist_path)
    {
        $this->temporary_video_path = $temporary_video_path;
        $this->playlist_path = $playlist_path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $hash = random_bytes(10);

        $lowBitrateFormat  = (new X264('aac','libx264'))->setKiloBitrate(400);
        $mediumBitrateFormat  = (new X264('aac','libx264'))->setKiloBitrate(1500);
        $highBitrateFormat  = (new X264('aac','libx264'))->setKiloBitrate(2500);

        FFMpeg::fromDisk('local')
          ->open($this->temporary_video_path)
          ->exportForHLS()
          ->toDisk('digitalocean')
          ->addFormat($lowBitrateFormat, function($media){
            $media->addFilter(function ($filters, $in, $out) {
                $filters->custom($in, "scale=640:360,fps=20", $out); // $in, $parameters, $out
            });
          })
          ->addFormat($mediumBitrateFormat, function($media){
            $media->addFilter(function ($filters, $in, $out) {
                $filters->custom($in, "scale=960:540,fps=20", $out); // $in, $parameters, $out
            });
          })
          ->addFormat($highBitrateFormat, function($media){
            $media->addFilter(function ($filters, $in, $out) {
              $filters->custom($in, "scale=1280:720,fps=20", $out); // $in, $parameters, $out
            });
          })
          ->save($this->playlist_path);
         // Storage::disk('local')->delete($this->temporary_video_path);
         // FFMpeg::cleanupTemporaryFiles();
    }
}
