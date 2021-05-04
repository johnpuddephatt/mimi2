@component('mail::message')

@component('mail::avatar')
{{ url($comment->user->photo) }}
@endcomponent

# “{{ \Illuminate\Support\Str::limit($comment->value, 150, $end='...') }}...”
## {{$comment->user->first_name}} just replied to you in **“{{ $comment->reply->lesson->title }}”**

@component('mail::button', ['url' => route('lesson.reply', ['course' => $comment->reply->lesson->week->course->id, 'lesson' => $comment->reply->lesson->id, 'reply_id' => $comment->reply->id ]) ])

Read and reply

@endcomponent

@endcomponent
