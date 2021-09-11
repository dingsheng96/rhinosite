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


                {{-- member --}}
                @auth('web')

                @if (Auth::user()->hasVerifiedEmail())
                <li class="nav-item">
                    <a href="{{ route('app.dashboard') }}" class="nav-button">{{ __('labels.return_dashboard') }}</a>
                </li>
                @endif

                <li class="nav-item">
                    <a href="#" class="nav-button" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                        {{ __('labels.logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                @endauth

                {{-- merchant --}}
                @auth('merchant')
                @if (Auth::user()->hasVerifiedEmail())
                @if (Auth::user()->has_approved_details && !Auth::user()->active_subscription)
                <li class="nav-item">
                    <a href="{{ route('merchant.subscriptions.index') }}" class="nav-button">{{ __('labels.subscribe_plan') }}</a>
                </li>
                @elseif(Auth::user()->has_approved_details && Auth::user()->active_subscription)
                <li class="nav-item">
                    <a href="{{ route('merchant.dashboard') }}" class="nav-button">{{ __('labels.return_dashboard') }}</a>
                </li>
                @endif
                @endif

                <li class="nav-item">
                    <a href="#" class="nav-button" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                        {{ __('labels.logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('merchant.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>

                @endauth

                {{-- admin --}}
                @auth('admin')
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-button">{{ __('labels.return_dashboard') }}</a>
                </li>
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
                    <a href="{{ route('login') }}" class="nav-button">{{ __('modules.login') }}</a>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>