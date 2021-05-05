@extends('layouts.static')

@section('content')

  <section class="section is-medium">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
          <div class="box">
            <h3 class="title has-text-centered">Activities 🔭</h3>
            <p class="subtitle has-text-centered">Take a look at what’s been happening</p>
            <div class="panel is-shadowless is-bordered">
              @foreach($activities as $activity)
                @if($activity->subject)
                 <div class="panel-block is-justify-between">
                  @if(class_basename($activity->subject_type) == 'Comment')
                    <figure class="image is-64x64 mr-2 is-align-self-flex-start is-flex-shrink-0">
                        <img class="is-rounded" src="{{ $activity->causer->photo }}" alt="Image">
                    </figure>
                    <div class="media-content mr-2">
                        <p>
                          <strong>{{ $activity->causer->first_name }}</strong> commented on
                          @if( $activity->subject->reply->user->id == $activity->causer->id )
                            their own reply:
                          @else
                            {{ $activity->subject->reply->user->first_name }}’s reply:<p>
                          @endif
                        </p>
                        <div class="is-italic is-size-7 mb-2">{!!$activity->properties['attributes']['value'] !!}</div>
                        <div class="is-size-7 has-text-grey">{{$activity->created_at->diffForHumans()}}</div>
                    </div>
                    @if($activity->subject)
                      <a class="button" href="{{ route('section.reply', [
                        'course' => $activity->subject->reply->lesson->week->course->id,
                        'lesson' => $activity->subject->reply->lesson->id,
                        'week' => $activity->subject->reply->lesson->week,
                        'section' => $activity->subject->reply->lesson->sections->where('is_chatroom', true)->first()->id,
                        'reply' => $activity->subject->reply->id ]) }}">View</a>
                    @else
                      This entry has since been deleted
                    @endif

                  @endif

                  @if(class_basename($activity->subject_type) == 'Reply')
                    @if($activity->subject->reply)
                      {{-- TEACHER FEEDBACK --}}
                      <figure class="image is-64x64 mr-2 is-align-self-flex-start is-flex-shrink-0">
                          <img class="is-rounded" src="{{ $activity->subject->user->photo }}" alt="Image">
                      </figure>
                      <div class="media-content mr-2">
                          <p><strong>{{ $activity->subject->user->first_name }}</strong> gave feedback to {{ $activity->subject->reply->user->first_name }}</strong></p>
                          <div class="is-size-7 has-text-grey">{{$activity->created_at->diffForHumans()}}</div>
                      </div>
                      @if($activity->subject)
                        <a class="button" href="{{ route('section.reply', [
                          'course' => $activity->subject->reply->lesson->week->course->id,
                          'week' => $activity->subject->reply->lesson->week,
                          'lesson' => $activity->subject->reply->lesson->id,
                          'section' => $activity->subject->reply->lesson->sections->where('is_chatroom', true)->first()->id,
                          'reply' => $activity->subject->reply->id,
                          'show_feedback' => true ]) }}">View</a>
                      @else
                        This entry has since been deleted
                      @endif

                    @else
                      {{-- STUDENT REPLY --}}
                      <figure class="image is-64x64 mr-2 is-align-self-flex-start is-flex-shrink-0">
                          <img class="is-rounded" src="{{ $activity->subject->user->photo }}" alt="Image">
                      </figure>
                      <div class="media-content mr-2">
                          <p><strong>{{ $activity->subject->user->first_name }}</strong> posted a reply</p>
                          <div class="is-size-7 has-text-grey">{{$activity->created_at->diffForHumans()}}</div>
                      </div>

                      @if($activity->subject)
                        <a class="button" href="{{ route('section.reply', [
                          'course' => $activity->subject->lesson->week->course->id,
                          'lesson' => $activity->subject->lesson->id,
                          'week' => $activity->subject->lesson->week,
                          'section' => $activity->subject->lesson->sections->where('is_chatroom', true)->first()->id,
                          'reply' => $activity->subject->id,
                          'show_feedback' => false ]) }}">View</a>
                        @else
                          This entry has since been deleted
                        @endif
                    @endif
                  @endif
                </div>
              @endif
              @endforeach
            </div>
            {{ $activities->links() }}
          </div>
        </div>
      </div>
    </div>
  </section>


@endsection
