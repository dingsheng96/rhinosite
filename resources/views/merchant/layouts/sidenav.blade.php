<aside class="main-sidebar sidebar-dark-orange elevation-2">

    <a href="{{ route('app.home') }}" class="brand-link navbar-orange" target="_blank">
        <img src="{{ asset('assets/logo.png') }}" alt="logo" class="brand-text d-block mx-auto my-0" style="max-width: 55%;">
    </a>

    <div class="sidebar">

        <nav class="mt-2">

            <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-flat" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-header">{{ __('modules.general') }}</li>

                <li class="nav-item">
                    <a href="{{ route('merchant.dashboard') }}" class="nav-link {{ Nav::hasSegment('dashboard', 1, 'active') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('modules.dashboard') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('merchant.ads-boosters.index') }}" class="nav-link {{ Nav::hasSegment('ads-boosters', 1, 'active') }}">
                        <i class="nav-icon fas fa-rocket"></i>
                        <p>{{ __('modules.ads') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('merchant.projects.index') }}" class="nav-link {{ Nav::hasSegment('projects', 1, 'active') }}">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>{{ trans_choice('modules.project', 2) }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ __('modules.ecommerce') }}</li>

                <li class="nav-item">
                    <a href="{{ route('merchant.products.index') }}" class="nav-link {{ Nav::hasSegment('products', 1, 'active') }}">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>{{ trans_choice('modules.product', 2) }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('merchant.orders.index') }}" class="nav-link {{ Nav::hasSegment('orders', 1, 'active') }}">
                        <i class="nav-icon fas fa-shipping-fast"></i>
                        <p>{{ trans_choice('modules.order', 2) }}</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>