<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <a href="{{ route('home') }}" class="Convertedin-logo-a">
                        <img src="{{ asset('images/logo.png') }}" class="Convertedin-logo" alt="" style="width: 40%">
                    </a>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear">
                            <span class="block m-t-xs">
                                {{trans('navigation.welcome')}} <strong
                                    class="font-bold">{{\Auth::user()->name}}</strong>
                            </span>
                            <span class="text-muted text-xs block">
                                <b class="caret"></b> {{ \Auth::user()->user_type_translated }}
                            </span>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="{{ url('/logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{trans('navigation.logout')}}</a>
                        </li>
                    </ul>
                </div>

                <div class="logo-element">
                    DM
                </div>
            </li>

            <li class="{{ isActiveRoute('home') }}">
                <a href="{{ route('home') }}">
                    <i class="fa fa-object-group"></i>
                    <span class="nav-label">Tasks</span>
                </a>
            </li>
            @if(auth()->user()->hasRole('Admin'))
                <li class="{{ isActiveRoute('home') }}">
                    <a href="{{ route('statistics') }}">
                        <i class="fa fa-object-group"></i>
                        <span class="nav-label">Statistics</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</nav>
