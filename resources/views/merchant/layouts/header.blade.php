<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 col-12">
                <h1 class="m-0 text-dark">{{ $title ?? '-' }}</h1>
            </div>
            <div class="col-sm-6 col-12">
                @if(!Nav::isRoute('dashboard'))
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('merchant.dashboard') }}">{{ __('modules.dashboard') }}</a></li>
                    @if (isset($parent_title))
                    <li class="breadcrumb-item active">{{ $parent_title ?? '-' }}</li>
                    @endif
                    <li class="breadcrumb-item active">{{ $title ?? '-' }}</li>
                </ol>
                @endif
            </div>
        </div>
    </div>
</div>