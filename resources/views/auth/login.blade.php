@extends('layouts.static')

@section('content')

   <section class="section is-medium">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-6-tablet is-5-desktop is-4-widescreen is-paddingless">
          <form action="{{ isset($_GET['course']) ? '/login?course=' . $_GET['course'] : '/login' }}" method="post" class="box">
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
                <input name="email" type="email" placeholder="e.g. bobsmith@gmail.com" class="input @error('email') is-danger @enderror" required>
                <span class="icon is-left"><i class="mdi mdi-email mdi-24px"></i></span>
              </div>

            </div>
            <div class="field">
              <label for="password" class="label">Password</label>
              <div class="control has-icons-left">
                <input name="password" type="password" placeholder="*******" class="input @error('password') is-danger @enderror" required>
                <span class="icon is-left"><i class="mdi mdi-key mdi-24px"></i></span>
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
