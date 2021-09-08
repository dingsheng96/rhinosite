@extends('admin.layouts.master', ['title' => trans_choice('modules.role', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title font-weight-bold">
                        {{ __('modules.view', ['module' => trans_choice('modules.role', 1)]) }}
                    </h3>
                </div>

                <div class="card-body">

                    <div class="form-group row">
                        <label for="name" class="col-form-label col-sm-2">{{ __('labels.name') }}</label>
                        <div class="col-sm-10">
                            <span id="name" class="form-control-plaintext">{{  $role->name }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="col-form-label col-sm-2">{{ __('labels.description') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="description">{!! nl2br($role->description) !!}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="table-responsive">
                            <label for="tbl_permission" class="col-form-label">{{ trans_choice('labels.permission', 2) }}</label>
                            <div class="table-responsive" id="tbl_permission">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <div class="icheck-primary">
                                                    <input type="checkbox" name="select_all" id="select_all" class="select-all-toggle">
                                                    <label for="select_all">{{ __('labels.select_all') }}</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            @foreach ($actions as $action)
                                            <td class="font-weight-bold">
                                                {{ Str::title($action->action) }}
                                            </td>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="select-all-container">
                                        @foreach ($modules as $module)
                                        <tr>
                                            <td class="font-weight-bold">{{ $module->display }}</td>
                                            @foreach ($module->permissions as $permission)
                                            <td>
                                                @if (collect($role->permissions->pluck('id'))->contains($permission->id))
                                                <i class="fas fa-check-circle text-success"></i>
                                                @else
                                                <i class="fas fa-times-circle text-danger"></i>
                                                @endif
                                                {{ $permission->display }}
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ route('admin.roles.index') }}" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection