<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">

        @merchant
        <li class="nav-item dropdown">
            <a class="nav-link" href="{{ route('ecommerce.carts.index') }}">
                <h5 class="mx-2"><i class="fas fa-shopping-cart"></i></h5>
                <span class="badge badge-danger navbar-badge rounded-circle">{{ Auth::user()->cart_item_count }}</span>
            </a>
        </li>
        @endmerchant

        <li class="nav-item dropdown">
            <a data-toggle="dropdown" class="nav-link" href="#">
                <h5><i class="fas fa-cog"></i></h5>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item" onclick="event.preventDefault(); logoutAlert('{{ __('labels.confirm_question') }}');">
                    <i class="fas fa-sign-out-alt mr-2" style="color: red;"></i>
                    <span>{{ __('labels.logout') }}</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>

</nav>