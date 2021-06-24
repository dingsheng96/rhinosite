@extends('layouts.master', ['parent_title' => trans_choice('modules.setting', 2), 'title' => trans_choice('modules.submodules.country', 2)])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    @can('country.create')
                    <span class="card-tools">
                        <a href="#countryModal" class="btn btn-outline-primary btn-rounded-corner" data-toggle="modal">
                            <i class="fas fa-plus"></i>
                            {{ __('modules.create', ['module' => trans_choice('modules.submodules.country', 1)]) }}
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

@includeWhen(Auth::user()->can('country.create'), 'settings.country.create')

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush