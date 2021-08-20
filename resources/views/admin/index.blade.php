@extends('layouts.master', ['title' => trans_choice('modules.admin', 2)])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    @can('admin.create')
                    <span class="card-tools">
                        <a href="#createAdminModal" class="btn btn-outline-primary btn-rounded-corner" data-toggle="modal">
                            <i class="fas fa-plus"></i>
                            {{ __('modules.create', ['module' => trans_choice('modules.admin', 1)]) }}
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

@includeWhen(Auth::user()->can('admin.create'), 'admin.create', compact('statuses'))
@includeWhen(Auth::user()->can('admin.update'), 'admin.edit', compact('statuses'))

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}

@if ($errors->has('create.*'))
<script type="text/javascript">
    $(window).on('load', function () {
        $('#createAdminModal').modal('show');
    });
</script>
@endif

@if ($errors->has('update.*'))
<script type="text/javascript">
    $(window).on('load', function () {
        $('#updateAdminModal').modal('show');
    });
</script>
@endif
@endpush