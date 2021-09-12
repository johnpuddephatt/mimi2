@component('mail::message')

@component('mail::avatar')
{{ url($comment->user->photo) }}
@endcomponent

<h1>“{!! \Illuminate\Support\Str::limit(strip_tags($comment->value), 150, $end='...') !!}”</h1>

## {{$comment->user->first_name}} just replied to your comment in **{{ $comment->reply->lesson->title }}**

@component('mail::button', ['url' => route('section.reply', ['cohort' => $comment->reply->cohort_id, 'course' => $comment->reply->lesson->week->course->id, 'week' => $comment->reply->lesson->week->number, 'lesson' => $comment->reply->lesson->id, 'section' => $comment->reply->lesson->sections()->where('is_chatroom', true)->first()->id, 'reply' => $comment->reply->id ]) ])

Read and reply

@endcomponent

@endcomponent
