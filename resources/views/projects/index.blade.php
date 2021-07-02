@extends('layouts.master', ['title' => trans_choice('modules.project', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">
                        {{ __('modules.list', ['module' => trans_choice('modules.project', 2)]) }}
                    </h3>

                    @can('project.create')
                    <div class="card-tools">
                        <a href="{{ route('projects.create') }}" class="btn btn-outline-primary float-md-right btn-rounded-corner">
                            <i class="fas fa-plus"></i>
                            {{ __('modules.create', ['module' => trans_choice('modules.project', 1)]) }}
                        </a>
                    </div>
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