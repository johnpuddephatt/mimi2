<div class="editor-js-block editor-js-block__translate-block">

  @foreach($data['content'] as $row)
  <div class="editor-js-block__translate-block--row">
    <p class="editor-js-block__translate-block--row--original">{!! $row['original'] !!}</p>
    <p class="editor-js-block__translate-block--row--translated">{!! $row['translated'] !!}</p>
  </div>
  @endforeach
</div>