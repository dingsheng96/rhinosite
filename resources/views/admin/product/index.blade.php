@extends('admin.layouts.master', [ 'title' => trans_choice('modules.product', 2)])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.list', ['module' => trans_choice('modules.product', 2)]) }}</h3>
                    @can('product.create')
                    <span class="card-tools">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary btn-rounded-corner">
                            <i class="fas fa-plus"></i>
                            {{ __('modules.create', ['module' => trans_choice('modules.product', 1)]) }}
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