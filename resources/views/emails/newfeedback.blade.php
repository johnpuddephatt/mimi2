@component('mail::message')

# Hi {{ $feedback->reply->user->first_name }},
## You’ve just received feedback in **“{{ $feedback->reply->lesson->title }}”**

@component('mail::button', ['url' => route('section.reply', ['course' => $feedback->reply->lesson->week->course->id, 'week' => $feedback->reply->lesson->week->number, 'lesson' => $feedback->reply->lesson->id, 'section' => $feedback->reply->lesson->sections()->where('is_chatroom', true)->first()->id, 'reply' => $feedback->reply->id, 'show_feedback' => true ]) ])

See your feedback

@endcomponent

@endcomponent
