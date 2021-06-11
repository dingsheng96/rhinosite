<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 col-12">
                <h1 class="m-0 text-dark">{{ $current_module ?? '-' }}</h1>
            </div>
            <div class="col-sm-6 col-12">
                @if(!Nav::isRoute('dashboard'))
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('modules.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ $current_module ?? '-' }}</li>
                </ol>
                @endif
            </div>
        </div>
    </div>
</div>