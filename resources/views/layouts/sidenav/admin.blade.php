@canany(['project.create', 'project.read', 'project.update', 'project.delete'])
<li class="nav-item">
    <a href="{{ route('projects.index') }}" class="nav-link {{ Nav::hasSegment('projects', 1, 'active') }}">
        <i class="nav-icon fas fa-briefcase"></i>
        <p>{{ trans_choice('modules.project', 2) }}</p>
    </a>
</li>
@endcanany

@canany(['ads.create', 'ads.read', 'ads.update', 'ads.delete'])
<li class="nav-item">
    <a href="{{ route('ads.index') }}" class="nav-link {{ Nav::hasSegment('ads', 1, 'active') }}">
        <i class="nav-icon fas fa-ad"></i>
        <p>{{ __('modules.ads') }}</p>
    </a>
</li>
@endcanany

@canany([
'product.create', 'product.read'. 'product.update', 'product.delete',
'package.create', 'package.read'. 'package.update', 'package.delete',
'order.create', 'order.read'. 'order.update', 'order.delete'
])
<li class="nav-item has-treeview {{ Nav::hasSegment(['ecommerce'], 1, 'menu-open') }}">
    <a href="#" class="nav-link {{ Nav::hasSegment(['ecommerce'], 1, 'active') }}">
        <i class="nav-icon fas fa-shopping-bag"></i>
        <p>
            {{ __('modules.ecommerce') }}
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @canany(['product.create', 'product.read'. 'product.update', 'product.delete'])
        <li class="nav-item">
            <a href="{{ route('ecommerce.products.index') }}" class="nav-link {{ Nav::hasSegment('products', 2, 'active') }}">
                <p>{{ trans_choice('modules.submodules.product', 2) }}</p>
            </a>
        </li>
        @endcanany
        @canany(['package.create', 'package.read'. 'package.update', 'package.delete'])
        <li class="nav-item">
            <a href="{{ route('ecommerce.packages.index') }}" class="nav-link {{ Nav::hasSegment('packages', 2, 'active') }}">
                <p>{{ trans_choice('modules.submodules.package', 2) }}</p>
            </a>
        </li>
        @endcanany
        @canany(['order.create', 'order.read'. 'order.update', 'order.delete'])
        <li class="nav-item">
            <a href="{{ route('ecommerce.orders.index') }}" class="nav-link {{ Nav::hasSegment('orders', 2, 'active') }}">
                <p>{{ trans_choice('modules.submodules.order', 2) }}</p>
            </a>
        </li>
        @endcanany
    </ul>
</li>
@endcanany

@canany(['merchant.create', 'merchant.update'])
<li class="nav-item">
    <a href="{{ route('verifications.index') }}" class="nav-link {{ Nav::hasSegment('verifications', 1, 'active') }}">
        <i class="nav-icon fas fa-id-card"></i>
        <p>{{ trans_choice('modules.verification', 2) }}</p>
        <span class="badge badge-pill badge-light px-2 bg-orange right">{{ $verifications_count ?? 0 }}</span>
    </a>
</li>
@endcanany

@canany([
'admin.create', 'admin.read'. 'admin.update', 'admin.delete',
'member.create', 'member.read'. 'member.update', 'member.delete',
'merchant.create', 'merchant.read'. 'merchant.update', 'merchant.delete'
])
<li class="nav-item has-treeview {{ Nav::hasSegment(['users'], 1, 'menu-open') }}">
    <a href="#" class="nav-link {{ Nav::hasSegment(['users'], 1, 'active') }}">
        <i class="nav-icon fas fa-users"></i>
        <p>
            {{ trans_choice('modules.user', 2) }}
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @canany(['admin.create', 'admin.read'. 'admin.update', 'admin.delete'])
        <li class="nav-item">
            <a href="{{ route('users.admins.index') }}" class="nav-link {{ Nav::hasSegment('admins', 2, 'active') }}">
                <p>{{ trans_choice('modules.submodules.admin', 2) }}</p>
            </a>
        </li>
        @endcanany
        @canany(['member.create', 'member.read'. 'member.update', 'member.delete'])
        <li class="nav-item">
            <a href="{{ route('users.members.index') }}" class="nav-link {{ Nav::hasSegment('members', 2, 'active') }}">
                <p>{{ trans_choice('modules.submodules.member', 2) }}</p>
            </a>
        </li>
        @endcanany
        @canany(['merchant.create', 'merchant.read'. 'merchant.update', 'merchant.delete'])
        <li class="nav-item">
            <a href="{{ route('users.merchants.index') }}" class="nav-link {{ Nav::hasSegment('merchants', 2, 'active') }}">
                <p>{{ trans_choice('modules.submodules.merchant', 2) }}</p>
            </a>
        </li>
        @endcanany
    </ul>
</li>
@endcanany

@canany([
'country.create', 'country.read'. 'country.update', 'country.delete',
'currency.create', 'currency.read'. 'currency.update', 'currency.delete',
'role.create', 'role.read'. 'role.update', 'role.delete',
'category.create', 'category.read', 'category.update', 'category.delete',
])
<li class="nav-item has-treeview {{ Nav::hasSegment(['settings'], 1, 'menu-open') }}">
    <a href="#" class="nav-link {{ Nav::hasSegment(['settings'], 1, 'active') }}">
        <i class="nav-icon fas fa-cogs"></i>
        <p>
            {{ trans_choice('modules.setting', 2) }}
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @canany(['role.create', 'role.read'. 'role.update', 'role.delete'])
        <li class="nav-item">
            <a href="{{ route('settings.roles.index') }}" class="nav-link {{ Nav::hasSegment('roles', 2, 'active') }}">
                <p>{{ trans_choice('modules.submodules.role', 2) }}</p>
            </a>
        </li>
        @endcanany
        @canany(['category.create', 'category.read'. 'category.update', 'category.delete'])
        <li class="nav-item">
            <a href="{{ route('settings.categories.index') }}" class="nav-link {{ Nav::hasSegment('categories', 2, 'active') }}">
                <p>{{ trans_choice('modules.submodules.category', 2) }}</p>
            </a>
        </li>
        @endcanany
        @canany(['currency.create', 'currency.read'. 'currency.update', 'currency.delete'])
        <li class="nav-item">
            <a href="{{ route('settings.currencies.index') }}" class="nav-link {{ Nav::hasSegment('currencies', 2, 'active') }}">
                <p>{{ trans_choice('modules.submodules.currency', 2) }}</p>
            </a>
        </li>
        @endcanany
        @canany(['country.create', 'country.read'. 'country.update', 'country.delete'])
        <li class="nav-item">
            <a href="{{ route('settings.countries.index') }}" class="nav-link {{ Nav::hasSegment('countries', 2, 'active') }}">
                <p>{{ trans_choice('modules.submodules.country', 2) }}</p>
            </a>
        </li>
        @endcanany
        @canany(['activity_log.create', 'activity_log.read'. 'activity_log.update', 'activity_log.delete'])
        <li class="nav-item">
            <a href="{{ route('settings.activity-logs.index') }}" class="nav-link {{ Nav::hasSegment('activity-logs', 2, 'active') }}">
                <p>{{ __('modules.submodules.activity_logs') }}</p>
            </a>
        </li>
        @endcanany
    </ul>
</li>
@endcanany