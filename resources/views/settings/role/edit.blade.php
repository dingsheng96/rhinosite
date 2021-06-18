@extends('layouts.master', ['parent_title' => trans_choice('modules.setting', 2), 'title' => trans_choice('modules.submodules.role', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.edit', ['module' => trans_choice('modules.submodules.role', 1)]) }}</h3>
                </div>

                <form action="{{ route('settings.roles.update', ['role' => $role->id]) }}" method="post" enctype="multipart/form-data" role="form">
                    @csrf
                    @method('put')

                    <div class="card-body">

                        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="role-tab" data-toggle="pill" href="#role" role="tab" aria-controls="role-tab" aria-selected="true">{{ __('labels.role') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="permission-tab" data-toggle="pill" href="#permission" role="tab" aria-controls="permission" aria-selected="false">{{ __('labels.permissions') }}</a>
                            </li>
                        </ul>

                        <div class="tab-content py-3" id="custom-content-below-tabContent">
                            <div class="tab-pane fade show active" id="role" role="tabpanel" aria-labelledby="role-tab">
                                <div class=" form-group">
                                    <label for="name" class="col-form-label">{{ __('labels.name') }}</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description" class="col-form-label">{{ __('labels.description') }}</label>
                                    <textarea name="description" id="description" cols="100" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $role->description) }}</textarea>
                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="permission" role="tabpanel" aria-labelledby="permission-tab">
                                <div class="table-responsive">
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
                                                <td class="font-weight-bold">{{ Str::title($module->name) }}</td>
                                                @foreach ($module->permissions as $permission)
                                                <td>
                                                    <div class="icheck-primary">
                                                        <input type="checkbox" name="permissions[]" id="permission_{{ $permission->id }}" value="{{ $permission->id }}" {{ collect($role->permissions()->pluck('id'))->contains($permission->id) ? 'checked' : null }}>
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
                        <a role="button" href="{{ route('settings.roles.index') }}" class="btn btn-light mx-2">
                            <i class="fas fa-times"></i>
                            {{ __('labels.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-outline-primary">
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