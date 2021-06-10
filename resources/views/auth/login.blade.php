@extends('layouts.master', ['title' => __('modules.login'), 'body' => 'login-page'])

@section('content')

<div class="login-box">

    <div class="login-logo">
        <a href=""><b>Admin</b>LTE</a>
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">{{ __('labels.start_session') }}</p>

            <form action="{{ route('login') }}" method="post" role="form" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="email">{{ __('labels.email') }}</label>
                    <div class="input-group mb-3">
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">{{ __('labels.password') }}</label>
                    <div class="input-group mb-3">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">{{ __('labels.remember_me') }}</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">{{ __('labels.sign_in') }}</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

</div>

@endsection