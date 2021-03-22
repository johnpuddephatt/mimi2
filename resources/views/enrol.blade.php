@extends('layouts.static')

@section('content')
  <section class="section is-medium">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
          <div class="box">
            <h3 class="title has-text-centered">Registrati 💡️</h3>
            <p class="subtitle has-text-centered">Log in or register to access this course</p>

            <div class="buttons">
              <a class="button is-fullwidth" href="{{ route('register', ['course' => $course ])}}">Register a new account</a>
              <a class="button is-fullwidth" href="{{ route('login', ['course' => $course ])}}">Log in to your existing account</a>
            </div>

            <p>If this is your first time using the chat room, choose <strong>Register</strong>. If you’ve used it before, choose <strong>Log in</strong></p>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
