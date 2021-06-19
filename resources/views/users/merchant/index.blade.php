@extends('layouts.master', ['parent_title' => trans_choice('modules.user', 2), 'title' => trans_choice('modules.submodules.merchant', 2)])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    @can('merchant.create')
                    <span class="card-tools">
                        <a href="" class="btn btn-outline-primary" data-toggle="modal">
                            <i class="fas fa-clipboard"></i>
                            {{ __('labels.registration') . '('.$registrations_count.')' }}
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