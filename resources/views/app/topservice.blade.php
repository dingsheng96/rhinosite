<div id="merchant-category">
    <div class="container">
        <div class="d-flex px-3">
            <span>{{ __('app.top_search_services') }}</span>
            <ul>
                @forelse ($services->take(6) as $service)
                <li class="active">
                    <a href="{{ route('app.project.index', ['q' => $service->name]) }}" class="text-muted">{{ $service->name }}</a>
                </li>
                @empty
                @endforelse
            </ul>
        </div>
    </div>
</div>