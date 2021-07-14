@extends('layouts.master', ['title' => trans_choice('modules.category', 2)])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    @can('category.create')
                    <span class="card-tools">
                        <a href="#createCategoryModal" class="btn btn-outline-primary btn-rounded-corner" data-toggle="modal">
                            <i class="fas fa-plus"></i>
                            {{ __('modules.create', ['module' => trans_choice('modules.category', 1)]) }}
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

@includeWhen(Auth::user()->can('category.create'), 'category.create')
@includeWhen(Auth::user()->can('category.update'), 'category.edit')

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush