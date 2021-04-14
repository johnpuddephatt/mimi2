<div class="editor-js-block editor-js-block__audio">
  <figure>
    <audio controls class="editor-js-audio" src="{{ $data['file']['url'] }}"></audio>
    @if($data['title'])
      <figcaption class="editor-js-audio--description">{{ $data['title'] }} <a href="{{ $data['file']['url'] }}" download>Download this audio</a></figcaption>
    @endif
  </figure>
</div>
