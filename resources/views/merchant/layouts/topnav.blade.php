<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-shopping-cart"></i>
                <span class="badge badge-danger navbar-badge rounded-circle">{{ Auth::user()->cart_items_count }}</span>
            </a>
        </li>

        <li class="nav-item dropdown">
            <a data-toggle="dropdown" class="nav-link" href="#">
                <img src="https://ui-avatars.com/api/?background=f6993f&color=ffffff&size=30&rounded=true&name={{ str_replace(' ', '+', Auth::user()->name) }}" class="img-circle elevation-2" alt="user">
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('merchant.account.index') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2 text-cyan"></i>
                    <span>{{ __('labels.user_account') }}</span>
                </a>
                <a href="#" class="dropdown-item" onclick="event.preventDefault(); logoutAlert('{{ __('messages.confirm_question') }}');">
                    <i class="fas fa-sign-out-alt mr-2 text-red"></i>
                    <span>{{ __('labels.logout') }}</span>
                </a>
                <form id="logout-form" action="{{ route('merchant.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>

</nav>