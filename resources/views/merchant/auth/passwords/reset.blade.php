@extends('layouts.master', ['title' => 'Reset Password', 'body' => 'enduser'])

@section('content')
<div class="container" style="padding-top: 7rem; padding-bottom: 5rem;">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-body shadow-lg py-5">

                <h5 class="text-center mb-5">{{ __('Reset Password') }}</h5>

                <form method="POST" action="{{ route('merchant.password.update') }}" role="form" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="input-group mb-3">
                        <p class="login-text">{{ __('labels.email') }} <span class="text-danger">*</span></p>
                        <input type="email" name="email" id="email" value="{{ old('email', $email) }}" class="@error('email') is-invalid @enderror" required autocomplete="email">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <p class="login-text">{{ __('labels.password') }} <span class="text-danger">*</span></p>
                        <input type="password" name="password" id="password" value="{{ old('password') }}" class="@error('password') is-invalid @enderror" required autocomplete="new-password" autofocus>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <small>* {{ __('messages.password_format') }}</small>
                    </div>

                    <div class="input-group mb-3">
                        <p class="login-text">{{ __('labels.password_confirmation') }} <span class="text-danger">*</span></p>
                        <input type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" class="@error('password-confirmation') is-invalid @enderror" required autocomplete="new-password">
                    </div>

                    <div class="form-group row justify-content-center">
                        <button type="submit" class="btn btn-orange btn-lg">
                            {{ strtoupper(__('Reset Password')) }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection