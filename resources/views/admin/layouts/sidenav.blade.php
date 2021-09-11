<aside class="main-sidebar sidebar-dark-orange elevation-2">

    <a href="{{ route('app.home') }}" class="brand-link navbar-orange" target="_blank">
        <img src="{{ asset('storage/logo.png') }}" alt="logo" class="brand-text d-block mx-auto my-0" style="max-width: 55%;">
    </a>

    <div class="sidebar">

        <nav class="mt-2">

            <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-flat" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-header">{{ __('modules.general') }}</li>

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Nav::hasSegment('dashboard', 1, 'active') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('modules.dashboard') }}</p>
                    </a>
                </li>

                @canany(['ads.create', 'ads.read', 'ads.update', 'ads.delete'])
                <li class="nav-item">
                    <a href="{{ route('admin.ads-boosters.index') }}" class="nav-link {{ Nav::hasSegment('ads-boosters', 1, 'active') }}">
                        <i class="nav-icon fas fa-rocket"></i>
                        <p>{{ __('modules.ads') }}</p>
                    </a>
                </li>
                @endcanany

                @canany(['project.create', 'project.read', 'project.update', 'project.delete'])
                <li class="nav-item">
                    <a href="{{ route('admin.projects.index') }}" class="nav-link {{ Nav::hasSegment('projects', 1, 'active') }}">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>{{ trans_choice('modules.project', 2) }}</p>
                    </a>
                </li>
                @endcanany

                @canany(['merchant.update'])
                <li class="nav-item">
                    <a href="{{ route('admin.subscriptions.create') }}" class="nav-link {{ Nav::hasSegment('subscriptions', 1, 'active') }}">
                        <i class="nav-icon fas fa-bookmark"></i>
                        <p>{{ trans_choice('modules.subscription', 1) }}</p>
                    </a>
                </li>
                @endcanany

                @canany(['service.create', 'service.read', 'service.update', 'service.delete'])
                <li class="nav-item">
                    <a href="{{ route('admin.services.index') }}" class="nav-link {{ Nav::hasSegment('services', 1, 'active') }}">
                        <i class="nav-icon fas fa-cube"></i>
                        <p>{{ trans_choice('modules.service', 2) }}</p>
                    </a>
                </li>
                @endcanany

                {{-- ECOMMERCE --}}
                @canany(['product.create', 'product.read', 'product.update', 'product.delete', 'order.create', 'order.read', 'order.update', 'order.delete', 'package.create', 'package.read', 'package.update', 'package.delete'])
                <li class="nav-header">{{ __('modules.ecommerce') }}</li>

                @canany(['product.create', 'product.read', 'product.update', 'product.delete'])
                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" class="nav-link {{ Nav::hasSegment('products', 1, 'active') }}">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>{{ trans_choice('modules.product', 2) }}</p>
                    </a>
                </li>
                @endcanany

                @canany(['package.create', 'admin.package.read', 'package.update', 'package.delete'])
                <li class="nav-item">
                    <a href="{{ route('admin.packages.index') }}" class="nav-link {{ Nav::hasSegment('packages', 1, 'active') }}">
                        <i class="nav-icon fas fa-gift"></i>
                        <p>{{ trans_choice('modules.package', 2) }}</p>
                    </a>
                </li>
                @endcanany

                @canany(['order.create', 'order.read', 'order.update', 'order.delete'])
                <li class="nav-item">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link {{ Nav::hasSegment('orders', 1, 'active') }}">
                        <i class="nav-icon fas fa-shipping-fast"></i>
                        <p>{{ trans_choice('modules.order', 2) }}</p>
                    </a>
                </li>
                @endcanany

                @canany(['transaction.create', 'transaction.read', 'transaction.update', 'transaction.delete'])
                <li class="nav-item">
                    <a href="{{ route('admin.transactions.index') }}" class="nav-link {{ Nav::hasSegment('transactions', 1, 'active') }}">
                        <i class="nav-icon fas fa-random"></i>
                        <p>{{ trans_choice('modules.transaction', 2) }}</p>
                    </a>
                </li>
                @endcanany

                @endcanany
                {{-- END ECOMMERCE --}}

                {{-- USERS --}}
                @canany(['merchant.create', 'merchant.read', 'merchant.update', 'merchant.delete', 'member.create', 'member.read', 'member.update', 'member.delete', 'admin.create', 'admin.read', 'admin.update', 'admin.delete'])

                <li class="nav-header">{{ __('modules.users') }}</li>

                @canany(['merchant.create'])
                <li class="nav-item">
                    <a href="{{ route('admin.verifications.index') }}" class="nav-link {{ Nav::hasSegment('verifications', 1, 'active') }}">
                        <i class="nav-icon fas fa-id-card"></i>
                        <p>{{ trans_choice('modules.verification', 2) }}</p>
                        <span class="badge badge-pill badge-light px-2 bg-orange right">{{ $verifications_count ?? 0 }}</span>
                    </a>
                </li>
                @endcanany

                @canany(['merchant.read', 'merchant.update', 'merchant.delete'])
                <li class="nav-item">
                    <a href="{{ route('admin.merchants.index') }}" class="nav-link {{ Nav::hasSegment('merchants', 1, 'active') }}">
                        <i class="nav-icon fas fa-store"></i>
                        <p>{{ trans_choice('modules.merchant', 2) }}</p>
                    </a>
                </li>
                @endcanany

                @canany(['member.create', 'member.read', 'member.update', 'member.delete'])
                <li class="nav-item">
                    <a href="{{ route('admin.members.index') }}" class="nav-link {{ Nav::hasSegment('members', 1, 'active') }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>{{ trans_choice('modules.member', 2) }}</p>
                    </a>
                </li>
                @endcanany

                @canany(['admin.create', 'admin.read', 'admin.update', 'admin.delete'])
                <li class="nav-item">
                    <a href="{{ route('admin.admins.index') }}" class="nav-link {{ Nav::hasSegment('admins', 1, 'active') }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>{{ trans_choice('modules.admin', 2) }}</p>
                    </a>
                </li>
                @endcanany

                @endcanany
                {{-- END USERS --}}

                {{-- SETTINGS --}}
                @canany(['country.create', 'country.read', 'country.update', 'country.delete', 'currency.create', 'currency.read', 'currency.update', 'currency.delete', 'role.create', 'role.read', 'role.update', 'role.delete'])

                <li class="nav-header">{{ __('modules.settings') }}</li>

                @canany(['role.create', 'role.read', 'role.update', 'role.delete'])
                <li class="nav-item">
                    <a href="{{ route('admin.roles.index') }}" class="nav-link {{ Nav::hasSegment('roles', 1, 'active') }}">
                        <i class="nav-icon fas fa-user-tag"></i>
                        <p>{{ trans_choice('modules.role', 2) }}</p>
                    </a>
                </li>
                @endcanany

                @canany(['currency.create', 'currency.read', 'currency.update', 'currency.delete'])
                <li class="nav-item">
                    <a href="{{ route('admin.currencies.index') }}" class="nav-link {{ Nav::hasSegment('currencies', 1, 'active') }}">
                        <i class="nav-icon fas fa-yen-sign"></i>
                        <p>{{ trans_choice('modules.currency', 2) }}</p>
                    </a>
                </li>
                @endcanany

                @canany(['country.create', 'country.read', 'country.update', 'country.delete'])
                <li class="nav-item">
                    <a href="{{ route('admin.countries.index') }}" class="nav-link {{ Nav::hasSegment('countries', 1, 'active') }}">
                        <i class="nav-icon fas fa-globe-asia"></i>
                        <p>{{ trans_choice('modules.country', 2) }}</p>
                    </a>
                </li>
                @endcanany

                @endcanany

                {{-- END SETTINGS --}}

            </ul>
        </nav>
    </div>
</aside>