@extends('layouts.master', ['parent_title' => trans_choice('modules.setting', 2), 'title' => trans_choice('modules.submodules.role', 2)])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    @can('role.create')
                    <span class="card-tools">
                        <a href="#roleModal" class="btn btn-outline-primary" data-toggle="modal">
                            <i class="fas fa-plus"></i>
                            {{ __('labels.create', ['module' => trans_choice('modules.submodules.role', 1)]) }}
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

@includeWhen(Auth::user()->can('role.create'), 'settings.role.create')

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush