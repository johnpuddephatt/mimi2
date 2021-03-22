<div class="editor-js-block editor-js-block__video">
  <figure>
    <video-player source="{!! $data['file']['playlist'] !!}" type="application/x-mpegURL"></video-player>
  @if($data['caption'])
    <figcaption class="editor-js-video--caption">{{ $data['caption'] }}</figcaption>
  @endif
  </figure>
</div>
