@extends('layouts.static')

@section('content')
  <section class="section is-medium">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
          <div class="box">
            <h3 class="title has-text-centered">Corsi 🗂️</h3>
            @if(\Auth::user()->is_admin)
              <p class="subtitle has-text-centered">Hi Admin! Here are all the active courses</p>
            @else
              <p class="subtitle has-text-centered">You’re enrolled on the courses below</p>
            @endif

            @forelse($courses as $course)
              <a class="is-medium is-justify-between is-fullwidth is-outlined"
                href="{{ route('course.show', ['course' => $course->id ]) }}">

                  {{ $course->title }}
                  @if(Auth::user()->is_admin && $course->feedbackless_replies_count)<span class="tag is-primary ml-2" title="{{ $course->feedbackless_replies_count }} replies awaiting feedback">{{ $course->feedbackless_replies_count }} new</span>@endif
                  @if($course->archived)
                    <span class="tag is-light ml-2">Archived</span>
                  @endif

              </a>
            @empty
              <section class="section is-medium has-background-light has-text-centered">
                @if(Auth::user()->is_admin)
                  <a class="button" href="{{ route('course.new') }}">Add the first course</a>
                @else
                  <p>You’re not enrolled in any courses.</p>
                @endif

              </section>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
