@extends('layouts.master', ['parent_title' => trans_choice('modules.setting', 2), 'title' => trans_choice('modules.submodules.country', 2)])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    @can('country.create')
                    <span class="card-tools">
                        <a href="#countryModal" class="btn btn-outline-primary" data-toggle="modal">
                            <i class="fas fa-plus"></i>
                            {{ __('labels.create') }}
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

@includeWhen(Auth::user()->can('create.country'), 'settings.country.create')

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush