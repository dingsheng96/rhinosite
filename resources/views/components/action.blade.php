@if (empty($no_action))

<a role="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    {{ __('labels.action') }}
</a>
<div class="dropdown-menu dropdown-menu-right">

    {{-- view button --}}
    @isset($view)
    @can($view['permission'])
    <a role="button" href="{{ $view['route'] ?? '#' }}" class="dropdown-item" @isset($view['attribute']) {!! $view['attribute'] !!} @endisset>
        <i class="fas fa-book-open mr-2 text-blue"></i>
        {{ __('labels.view') }}
    </a>
    @endcan
    @endisset

    {{-- update button --}}
    @isset($update)
    @can($update['permission'])
    <a role="button" href="{{ $update['route'] ?? '#' }}" class="dropdown-item" @isset($update['attribute']) {!! $update['attribute'] !!} @endisset>
        <i class="fas fa-edit mr-2 text-purple"></i>
        {{ __('labels.edit') }}
    </a>
    @endcan
    @endisset

    {{-- delete button --}}
    @isset($delete)
    @can($delete['permission'])
    <a role="button" href="{{ $delete['route'] ?? '#' }}" class="dropdown-item" onclick="event.preventDefault(); deleteAlert('{{ __('messages.confirm_question') }}', '{{ __('messages.delete_info') }}', '{{ $delete['route'] }}')" @isset($delete['attribute']) {!! $delete['attribute'] !!} @endisset>
        <i class="fas fa-trash mr-2 text-red"></i>
        {{ __('labels.delete') }}
    </a>
    @endcan
    @endisset

    {{-- download button --}}
    @isset($download)
    <a role="button" href="{{ $download['route'] ?? '#' }}" class="dropdown-item" @isset($download['attribute']) {!! $download['attribute'] !!} @endisset>
        <i class="fas fa-download mr-2 text-cyan"></i>
        {{ __('labels.download') }}
    </a>
    @endisset

</div>

@endif