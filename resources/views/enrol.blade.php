@extends('layouts.static')

@section('content')
<section class="section is-medium">
  <div class="container">
    <div class="columns is-centered">
      <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
        <div class="box">
          <h3 class="title has-text-centered">Registrati 💡️</h3>
          <p class="subtitle has-text-centered">Log in or register to join this course</p>

          <div class="buttons mt-4">
            <a class="button is-primary is-medium is-fullwidth" href="{{ route('login', ['cohort' => $cohort ])}}">Log
              in to your account</a>
          </div>

          <p>If you haven’t created an account on the site yet, <a
              href="{{ route('register', ['cohort' => $cohort ])}}">you can register one here</a></p>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection