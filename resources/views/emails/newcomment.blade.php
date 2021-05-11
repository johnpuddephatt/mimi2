@component('mail::message')

@component('mail::avatar')
{{ url($comment->user->photo) }}
@endcomponent

# “{!! \Illuminate\Support\Str::limit($comment->value, 150, $end='...') !!}...”
## {{$comment->user->first_name}} just left you a comment in **“{{ $comment->reply->lesson->title }}”**

@component('mail::button', ['url' => route('section.reply', ['course' => $comment->reply->lesson->week->course->id, 'week' => $comment->reply->lesson->week->number, 'lesson' => $comment->reply->lesson->id, 'section' => $comment->reply->lesson->sections()->where('is_chatroom', true)->first()->id, 'reply' => $comment->reply->id ]) ])

Read and reply

@endcomponent

@endcomponent
