<nav class="navbar is-transparent is-fixed-top is-spaced">
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
            <div class="navbar-start"></div>

            <div class="navbar-end">
                @if (Auth::guest())

                @else
                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link" href="#">
                          <figure class="image is-24x24 mr-1">
                            <img src="{{ Auth::user()->photo }}" class="is-rounded"/>
                          </figure>
                          {{ Arr::random(['Ciao', 'Salve', 'Pronto']) }}, {{ Auth::user()->first_name }}!
                          @if(Auth::user()->is_admin)<span class="tag is-success">Admin</span>@endif
                          @if(Auth::user()->subscribed())<span class="tag is-success">Subscribed</span>@endif

                        </a>

                        <div class="navbar-dropdown">

                          @if(Auth::user()->is_admin)
                            <a class="navbar-item" href="{{ route('courses.manage') }}">
                              Manage Courses
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
                          @endif
                            <a class="navbar-item" href="{{ route('scheduler') }}">
                              Speaking club bookings
                            </a>
                            @if(Auth::user()->subscribed())
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
