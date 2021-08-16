<div id="merchant-category">
    <div class="container">
        <div class="d-flex align-items-xl-center px-3">
            <span>{{ __('app.top_search_services') }}</span>
            <ul>
                @forelse ($services->take(6) as $service)
                <li class="top-service">
                    <a href="{{ route('app.project.index', ['q' => $service->name]) }}" class="font-weight-bold">{{ $service->name }}</a>
                </li>
                @empty
                @endforelse
            </ul>
        </div>
    </div>
</div>