@extends('admin.layouts.master', ['title' => trans_choice('modules.member', 2)])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">
                        {{ __('modules.list', ['module' => trans_choice('modules.member', 1)]) }}
                    </h3>

                    @can('member.create')
                    <div class="card-tools">
                        <a href="{{ route('admin.members.create') }}" class="btn btn-outline-primary btn-rounded-corner">
                            <i class="fas fa-plus"></i>
                            {{ __('modules.create', ['module' => trans_choice('modules.member', 1)]) }}
                        </a>
                    </div>
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