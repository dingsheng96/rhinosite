@extends('layouts.master', ['parent_title' => trans_choice('modules.user', 2), 'title' => trans_choice('modules.submodules.merchant', 2)])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    @can('merchant.create')
                    <span class="card-tools">
                        <a href="{{ route('users.registrations.index', ['status' => 'pending']) }}" class="btn btn-outline-primary">
                            <i class="fas fa-clipboard-list"></i>
                            {{ trans_choice('modules.submodules.registration', 2) }}
                            <h5 class="d-inline"><span class="badge badge-danger rounded-circle">{{ $registrations_count ?? 0 }}</span></h5>
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

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush