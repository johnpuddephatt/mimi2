@component('mail::message')

# Hi {{ $feedback->reply->user->first_name }},
## You’ve just received feedback in **“{{ $feedback->reply->lesson->title }}”**

@component('mail::button', ['url' => route('lesson.reply', ['course' => $feedback->reply->lesson->course->id, 'lesson' => $feedback->reply->lesson->id, 'reply_id' => $feedback->reply->id, 'show_feedback' => true ]) ])

See your feedback

@endcomponent

@endcomponent
