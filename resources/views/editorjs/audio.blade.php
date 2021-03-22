<div class="editor-js-block editor-js-block__audio">
  <figure>
    <audio controls class="editor-js-audio" src="{{ $data['file']['url'] }}"></audio>
    @if($data['title'])
      <figcaption class="editor-js-audio--description">{{ $data['title'] }}</figcaption>
    @endif
  </figure>
</div>
