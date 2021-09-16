@foreach($blocks as $block)
@include('editorjs.' . $block['type'], ['data' => $block['data'], 'tunes' => isset($block['tunes']) ? $block['tunes'] :
null ])
@endforeach