@component('mail::message')

@component('mail::avatar')
{{ url($reply->user->photo) }}
@endcomponent

# {{ $reply->user->first_name }} just posted in<br> “{{ $reply->lesson->title }}”

@component('mail::button', ['url' => route('section.reply', ['cohort' => $reply->cohort_id , 'course' => $reply->lesson->week->course->id, 'week' => $reply->lesson->week->number, 'lesson' => $reply->lesson->id, 'section' => $reply->lesson->sections()->where('is_chatroom', true)->first()->id, 'reply' => $reply->id ]) ])

Watch this reply

@endcomponent

@endcomponent
