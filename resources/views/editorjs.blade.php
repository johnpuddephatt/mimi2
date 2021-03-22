@foreach($blocks as $block)
  @include('editorjs.' . $block['type'], ['data' => $block['data'] ])
@endforeach
