<div class="editor-js-block editor-js-block__embed">
  <figure>
    <iframe width="{{ $data['width'] }}" height="{{ $data['height'] }}" src="{{ $data['embed'] }}"></iframe>
  @if($data['caption'])
    <figcaption class="editor-js-embed--caption">{{ $data['caption'] }}</figcaption>
  @endif
  </figure>
</div>
