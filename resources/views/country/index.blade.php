@extends('layouts.master', ['parent_title' => trans_choice('modules.setting', 1), 'title' => trans_choice('modules.submodules.country', 1)])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <a href="" class="btn btn-light bg-orange">
                <i class="fas fa-plus"></i>
                {{ __('labels.create', ['module' => trans_choice('modules.submodules.country', 1)]) }}
            </a>
        </div>
    </div>

    <div class="row py-2">
        <div class="col-12">
            <div class="card">
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