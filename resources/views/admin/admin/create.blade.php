@extends('admin.layouts.master', ['title' => trans_choice('modules.member', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.create', ['module' => trans_choice('modules.member', 1)]) }}</h3>
                </div>

                <form action="{{ route('admin.admins.store') }}" method="post" role="form" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">

                        <div class="form-group">
                            <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-form-label">{{ __('labels.email') }} <span class="text-red">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="role" class="col-form-label">{{ __('labels.role') }} <span class="text-red">*</span></label>
                            <select name="role" id="role" class="form-control select2 @error('role') is-invalid @enderror">
                                @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role', 'active') == $role->id ? 'selected' : null }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status" class="col-form-label">{{ __('labels.status') }} <span class="text-red">*</span></label>
                            <select name="status" id="status" class="form-control select2 @error('status') is-invalid @enderror">
                                @foreach ($statuses as $index => $status)
                                <option value="{{ $index }}" {{ old('status', 'active') == $index ? 'selected' : null }}>{{ $status }}</option>
                                @endforeach
                            </select>
                            @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-form-label">{{ __('labels.password') }} <span class="text-red">*</span></label>
                            <input type="password" id="password" name="password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <small>* <strong>{{ __('messages.password_format') }}</strong></small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="col-form-label">{{ __('labels.password_confirmation') }} <span class="text-red">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a href="{{ route('admin.admins.index') }}" role="button" class="btn btn-light mx-2 btn-rounded-corner">
                            <i class="fas fa-caret-left"></i>
                            {{ __('labels.back') }}
                        </a>
                        <button type="submit" class="btn btn-outline-primary btn-rounded-corner">
                            <i class="fas fa-paper-plane"></i>
                            {{ __('labels.submit') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection