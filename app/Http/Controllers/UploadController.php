<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ConvertSectionVideoForStreaming;

use App\Models\Section;
use Illuminate\Http\Request;

class UploadController extends Controller
{
  public function store(Request $request) {

    if($request->audio) {
      $file_path = Section::$uploadPaths['audio'] . $request->audio->hashName();
      Storage::cloud()->put(Section::$uploadPaths['audio'], $request->audio);
      return response()->json([
        "success" => 1,
        "file" => [
          "name" => $request->audio->getClientOriginalName(),
          "url" => Storage::cloud()->url($file_path),
          "size" => $request->audio->getSize()
        ]
      ]);
    }

    if($request->image) {
      $file_path = Section::$uploadPaths['image'] . $request->image->hashName();
      Storage::cloud()->put(Section::$uploadPaths['image'] , $request->image);
      return response()->json([
        "success" => 1,
        "file" => [
          "url" => Storage::cloud()->url($file_path),
        ]
      ]);
    }

    if($request->video) {
      Storage::disk('local')->put(Section::$uploadPaths['video_temporary'], $request->video);

      $temporary_video_path = Section::$uploadPaths['video_temporary'] . $request->video->hashName();

      // $playlist_path = Storage::cloud()->url(Section::$uploadPaths['video_playlist'] . $request->video->hashName() . '.m3u8');
      $playlist_path = Section::$uploadPaths['video_playlist'] . $request->video->hashName() . '.m3u8';
      ConvertSectionVideoForStreaming::dispatch($temporary_video_path, $playlist_path);


      return response()->json([
        "success" => 1,
        "file" => [
          "url" => Storage::disk('local')->url($temporary_video_path),
          "playlist" => Storage::cloud()->url($playlist_path)
        ]
      ]);
    }

  }
}
