@extends('layouts.static')

@section('content')
  <section class="section is-medium">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
          <div class="box">
            <h3 class="title has-text-centered">Welcome ✨️</h3>
            <p class="subtitle has-text-centered">Something to be said.</p>
            <a class="button" href="{{ route('billing.start' )}}">Start</a>

            {{ Auth::user()->trial_ends_at }}
            @if(Auth::user()->onTrial())
              <p>I'm on trial!!</p>
            @endif
            @if(Auth::user()->onGenericTrial())
              <p>I'm on a generic trial!!</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
