@if (empty($no_action))

<a role="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    {{ __('labels.action') }}
</a>
<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

    {{-- view button --}}
    @isset($view)
    @can($view['permission'])
    <a role="button" href="{{ $view['route'] ?? '#' }}" class="dropdown-item" @isset($view['attribute']) {!! $view['attribute'] !!} @endisset>
        <i class="fas fa-eye mr-2 text-blue"></i>
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
    <a type="button" href="{{ $delete['route'] ?? '#' }}" class="dropdown-item" onclick="event.preventDefault(); deleteAlert('{{ __('labels.delete_confirm_question') }}', '{{ __('labels.delete_info') }}', '{{ $delete['route'] }}')" @isset($delete['attribute']) {!! $delete['attribute'] !!} @endisset>
        <i class="fas fa-trash mr-2 text-red"></i>
        {{ __('labels.delete') }}
    </a>
    @endcan
    @endisset

</div>

@endif