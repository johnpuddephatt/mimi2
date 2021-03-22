@component('mail::message')

@component('mail::avatar')
{{ url($reply->user->photo) }}
@endcomponent

# {{ $reply->user->first_name }} just posted in<br> “{{ $reply->lesson->title }}”

@component('mail::button', ['url' => route('lesson.reply', ['course' => $reply->lesson->course->id, 'lesson' => $reply->lesson->id, 'reply_id' => $reply->id ]) ])

Watch this reply

@endcomponent

@endcomponent
