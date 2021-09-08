@extends('admin.layouts.master', ['title' => trans_choice('modules.member', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.member', 1)]) }}</h3>
                </div>

                <div class="card-body">

                    <div class="form-group row">
                        <label for="name" class="col-form-label col-sm-2">{{ __('labels.name') }}</label>
                        <div class="col-sm-10">
                            <span id="name" class="form-control-plaintext">{{ $admin->name }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-form-label col-sm-2">{{ __('labels.email') }}</label>
                        <div class="col-sm-10">
                            <span id="email" class="form-control-plaintext">{{ $admin->email }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="role" class="col-form-label col-sm-2">{{ __('labels.role') }}</label>
                        <div class="col-sm-10">
                            <span id="role" class="form-control-plaintext">{{ $admin->roles->first()->name }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-form-label col-sm-2">{{ __('labels.status') }}</label>
                        <div class="col-sm-10">
                            <span id="status" class="form-control-plaintext">{!! $admin->status_label !!}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="last_login" class="col-form-label col-sm-2">{{ __('labels.last_login_at') }}</label>
                        <div class="col-sm-10">
                            <span id="last_login" class="form-control-plaintext">{{  $admin->last_login_at ?? '-' }}</span>
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a href="{{ route('admin.admins.index') }}" role="button" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection