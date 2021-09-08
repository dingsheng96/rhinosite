@if (Auth::guard('admin')->check() && (!isset($guest_view) || !$guest_view))

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">

        @merchant
        <li class="nav-item dropdown">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-shopping-cart"></i>
                <span class="badge badge-danger navbar-badge rounded-circle">{{ Auth::user()->cart_items_count }}</span>
            </a>
        </li>
        @endmerchant

        <li class="nav-item dropdown">
            <a data-toggle="dropdown" class="nav-link" href="#">
                <i class="fas fa-th-large"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('admin.account.index') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2 text-cyan"></i>
                    <span>{{ __('labels.user_account') }}</span>
                </a>
                <a href="#" class="dropdown-item" onclick="event.preventDefault(); logoutAlert('{{ __('messages.confirm_question') }}');">
                    <i class="fas fa-sign-out-alt mr-2 text-red"></i>
                    <span>{{ __('labels.logout') }}</span>
                </a>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>

</nav>

@else

<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('app.home') }}"><img src="{{ asset('storage/logo.png') }}" alt="rhinosite_logo" class="nav-logo"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item {{ Nav::hasSegment('', 1, 'active') }}">
                    <a href="{{ route('app.home') }}" class="nav-link">{{ __('modules.app.home') }}</a>
                </li>
                <li class="nav-item {{ Nav::hasSegment('project', 1, 'active') }}">
                    <a href="{{ route('app.project.index') }}" class="nav-link">{{ __('modules.app.merchant') }}</a>
                </li>
                <li class="nav-item {{ Nav::hasSegment('about', 1, 'active') }}">
                    <a href="{{ route('app.about') }}" class="nav-link">{{ __('modules.app.about') }}</a>
                </li>

                @auth

                @if (Auth::user()->hasVerifiedEmail())

                @merchant

                @if (!Auth::user()->active_subscription)
                <li class="nav-item">
                    <a href="{{ route('subscriptions.index') }}" class="nav-button">{{ __('labels.subscribe_plan') }}</a>
                </li>
                @elseif(!Auth::user()->has_approved_details)
                @else
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-button">{{ __('labels.return_dashboard') }}</a>
                </li>
                @endif

                @else {{-- NOT MERCHANT --}}
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-button">{{ __('labels.return_dashboard') }}</a>
                </li>
                @endmerchant

                @endif

                <li class="nav-item">
                    <a href="#" class="nav-button" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                        {{ __('labels.logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>

                @endauth

                @guest
                <li class="nav-item">
                    <a href="{{ route('app.partner') }}" class="nav-button">{{ __('modules.app.partner') }}</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.login') }}" class="nav-button">{{ __('modules.login') }}</a>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
@endif