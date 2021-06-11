<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a data-toggle="dropdown" class="nav-link border-0 bg-transparent" style="cursor: pointer;">
                <img src="https://ui-avatars.com/api/?background=f6993f&color=ffffff&size=30&rounded=true&name={{ str_replace(' ', '+', Auth::user()->name) }}" alt="user" class="img-circle elevation-2 mr-2">
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-2" style="color: red;"></i>
                    <span>{{ __('Logout') }}</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>

</nav>