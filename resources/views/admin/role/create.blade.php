@extends('admin.layouts.master', ['title' => trans_choice('modules.role', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title font-weight-bold">
                        {{ __('modules.create', ['module' => trans_choice('modules.role', 1)]) }}
                    </h3>
                </div>

                <form action="{{ route('admin.roles.store') }}" method="post" enctype="multipart/form-data" role="form">
                    @csrf

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">{{ __('labels.name') }}</label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description" class="col-form-label">{{ __('labels.description') }}</label>
                                    <textarea name="description" id="description" cols="100" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
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
                                                    <div class="icheck-primary">
                                                        <input type="checkbox" name="permissions[]" id="permission_{{ $permission->id }}" value="{{ $permission->id }}" {{ collect(old('permissions'))->contains($permission->id) ? 'checked' : null }}>
                                                        <label for="permission_{{ $permission->id }}">{{ $permission->display }}</label>
                                                    </div>
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
                        <a role="button" href="{{ route('admin.roles.index') }}" class="btn btn-light mx-2 btn-rounded-corner">
                            <i class="fas fa-times"></i>
                            {{ __('labels.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-outline-primary btn-rounded-corner">
                            <i class="fas fa-paper-plane"></i>
                            {{ __('labels.submit') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
        <div class="col-12 col-md-7"></div>
    </div>

</div>

@endsection