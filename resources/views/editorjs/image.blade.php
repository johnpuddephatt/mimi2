<div class="editor-js-block editor-js-block__image">
  <figure class="editor-js-image @if($data['withBackground']) with-background @endif @if($data['withBorder']) with-border @endif @if($data['stretched']) stretched @endif">
    <img src="{{ $data['file']['url'] }}" alt="{{ $data['caption'] }}">
    @if(isset($data['caption']))
      <figcaption>
        {!! $data['caption'] !!}
      </figcaption>
    @endif
  </figure>
</div>
