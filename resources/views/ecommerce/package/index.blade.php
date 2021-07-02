@extends('layouts.master', ['parent_title' => __('modules.ecommerce'), 'title' => trans_choice('modules.submodules.package', 2)])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.list', ['module' => trans_choice('modules.submodules.package', 2)]) }}</h3>
                    @can('package.create')
                    <span class="card-tools">
                        <a href="{{ route('ecommerce.packages.create') }}" class="btn btn-outline-primary btn-rounded-corner">
                            <i class="fas fa-plus"></i>
                            {{ __('modules.create', ['module' => trans_choice('modules.submodules.package', 1)]) }}
                        </a>
                    </span>
                    @endcan
                </div>
                <div class="card-body">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush