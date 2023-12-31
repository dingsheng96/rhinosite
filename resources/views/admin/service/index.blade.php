@extends('admin.layouts.master', ['title' => trans_choice('modules.service', 2)])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    @can('service.create')
                    <span class="card-tools">
                        <a href="#createServiceModal" class="btn btn-outline-primary btn-rounded-corner" data-toggle="modal">
                            <i class="fas fa-plus"></i>
                            {{ __('modules.create', ['module' => trans_choice('modules.service', 1)]) }}
                        </a>
                    </span>
                    @endcan
                </div>
                <div class="card-body table-responsive">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@includeWhen(Auth::user()->can('service.create'), 'admin.service.create')
@includeWhen(Auth::user()->can('service.update'), 'admin.service.edit')

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush