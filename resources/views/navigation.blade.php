<nav class="navbar is-transparent is-fixed-top is-spaced">

    @if(config('app.alert'))
      <div class="notification has-text-centered is-warning m-1 is-light">
        {!! config('app.alert') !!}
      </div>
    @endif

    <div class="container">
        <div class="navbar-brand">
            <a href="{{ url('/') }}" class="navbar-item">
               <img src="{{ asset('/images/logo.png') }}" alt="">
            </a>

            <div class="navbar-burger burger" data-target="navMenu">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <div class="navbar-menu" id="navMenu">
            <div class="navbar-start">
              @if(Auth::user())
                <a class="navbar-item" href="{{ route('course.index') }}">My courses</a>
                <a class="navbar-item" href="{{ route('scheduler') }}">Speaking club</a>
              @endif
            </div>

            <div class="navbar-end">
                @if (Auth::guest())

                @else
                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link" href="#">
                          <figure class="image is-24x24 mr-1">
                            <img src="{{ Auth::user()->photo }}" class="is-rounded"/>
                          </figure>
                          {{ Arr::random(['Ciao', 'Salve', 'Ehilà']) }}, {{ Auth::user()->first_name }}!
                          @if(Auth::user()->is_admin)<span class="tag is-success">Admin</span>@endif
                          @if(Auth::user()->subscribed())<span class="tag is-success">Subscribed</span>@endif
                        </a>

                        <div class="navbar-dropdown">

                          @if(Auth::user()->is_admin)
                            <a class="navbar-item" href="{{ route('courses.manage') }}">
                              Course manager
                            </a>
                            <a class="navbar-item" href="{{ route('chatroom.index') }}">
                              Chatroom manager
                            </a>
                            <a class="navbar-item" href="{{ route('admin.emails') }}">
                              Emails
                            </a>
                            <a class="navbar-item" href="/admin/activity">
                              Activity
                            </a>
                            <a class="navbar-item" href="/admin/logs">
                              Logs
                            </a>
                            <hr class="navbar-divider">
                          @endif
                            {{-- <a class="navbar-item" href="{{ route('scheduler') }}">
                              Speaking club bookings
                            </a> --}}
                            <a class="navbar-item" href="{{ route('profile.show') }}">
                              My profile
                            </a>
                            <a class="navbar-item" href="{{ route('user.chatrooms') }}">
                              My chatroom replies
                            </a>
                            @if(Auth::user()->subscribed() || (Auth::user()->subscription('default') && Auth::user()->subscription('default')->onGracePeriod()))
                              <a class="navbar-item" href="{{ route('billing.portal') }}">
                                Manage my subscription
                              </a>
                            @endif
                            <a class="navbar-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>
