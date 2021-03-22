<div class="editor-js-block editor-js-block__list">
  {!! ($data['style'] == 'unordered') ? '<ul>' : '<ol>' !!}
    @foreach($data['items'] as $item)
    <li>
      {{ $item }}
    </li>
    @endforeach
  {!! ($data['style'] == 'unordered') ? '</ul>' : '</ol>' !!}
</div>
