@extends('layouts.master', ['parent_title' => trans_choice('modules.setting', 2), 'title' => trans_choice('modules.submodules.currency', 2)])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    @can('currency.create')
                    <span class="card-tools">
                        <a href="{{ route('settings.currencies.create') }}" class="btn btn-outline-primary btn-rounded-corner">
                            <i class="fas fa-plus"></i>
                            {{ __('modules.create', ['module' => trans_choice('modules.submodules.currency', 1)]) }}
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