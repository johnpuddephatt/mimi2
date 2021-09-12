@extends('layouts.static')

@section('content')

<section class="section is-medium">
  <div class="container">
    <div class="columns is-centered">
      <div class="column is-6-tablet is-5-desktop is-4-widescreen is-paddingless">
        <form action="{{ isset($_GET['cohort']) ? '/login?cohort=' . $_GET['cohort'] : '/login' }}" method="post"
          class="box">
          @csrf
          <h3 class="title has-text-centered">Ciao! <span class="emoji">😃</span></h3>
          <p class="subtitle has-text-centered">You can log in below</p>

          @error('email')
          <p class="notification is-danger" role="alert">
            {{ $message }}
          </p>
          @enderror

          <div class="field">
            <label for="email" class="label">Email</label>
            <div class="control has-icons-left">
              <input name="email" type="email" placeholder="e.g. bobsmith@gmail.com"
                class="input @error('email') is-danger @enderror" required>
              <span class="icon is-left">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="feather feather-mail">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                  <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
              </span>
            </div>

          </div>
          <div class="field">
            <label for="password" class="label">Password</label>
            <div class="control has-icons-left">
              <input name="password" type="password" placeholder="*******"
                class="input @error('password') is-danger @enderror" required>
              <span class="icon is-left"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round" class="feather feather-lock">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg></span>
            </div>

          </div>
          <div class="field">
            <label for="" class="checkbox">
              <input name="remember" type="checkbox">
              Remember me
            </label>
          </div>

          <div class="field">
            <input type="submit" class="button is-primary is-fullwidth" value="Login">
          </div>
        </form>
        <div class="has-text-centered" class="control">
          <a href="{{ route('password.request') }}">
            Forgot Your Password?
          </a>
        </div>
      </div>
    </div>
  </div>
</section>


@endsection