@canany(['project.create', 'project.read', 'project.update', 'project.delete'])
<li class="nav-item">
    <a href="{{ route('projects.index') }}" class="nav-link {{ Nav::hasSegment('projects', 1, 'active') }}">
        <i class="nav-icon fas fa-briefcase"></i>
        <p>{{ trans_choice('modules.project', 2) }}</p>
    </a>
</li>
@endcanany

@canany(['order.create', 'order.read', 'order.update', 'order.delete'])
<li class="nav-item">
    <a href="{{ route('subscriptions.index') }}" class="nav-link {{ Nav::hasSegment('subscriptions', 1, 'active') }}">
        <i class="nav-icon fas fa-bookmark"></i>
        <p>{{ trans_choice('modules.subscription', 2) }}</p>
    </a>
</li>
@endcanany