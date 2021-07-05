<aside class="main-sidebar sidebar-dark-orange elevation-2">

    <a href="{{ route('dashboard') }}" class="brand-link navbar-orange">
        <img src="{{ asset('storage/logo.png') }}" alt="System Logo" class="brand-text d-block mx-auto my-0" style="max-width: 55%;">
    </a>

    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://ui-avatars.com/api/?background=f6993f&color=ffffff&size=30&rounded=true&name={{ str_replace(' ', '+', Auth::user()->name) }}" class="img-circle elevation-2" alt="user">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Str::limit(Auth::user()->name, 20, '...') }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Nav::hasSegment('dashboard', 1, 'active') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('modules.dashboard') }}</p>
                    </a>
                </li>

                @admin
                @include('layouts.sidenav.admin')
                @endadmin

                @merchant
                @include('layouts.sidenav.merchant')
                @endmerchant

            </ul>
        </nav>
    </div>
</aside>