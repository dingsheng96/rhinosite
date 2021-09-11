@extends('admin.layouts.master', ['title' => 'Reset Password', 'body' => 'enduser'])

@section('content')

<div class="container" style="padding-top: 7rem; padding-bottom: 7rem;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-body shadow-lg text-center py-5">

                <h5 class="mb-5">{{ __('Forgot Password') }}</h5>

                <p class="text-muted">
                    {{ __('You may submit your registered email to receive a password reset link.') }}
                </p>

                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group row justify-content-center">
                        <div class="col-md-8">
                            <input id="email" type="email" class="form-control form-control-lg text-center @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('labels.email') }}">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-orange btn-lg">
                            {{ strtoupper(__('Send Password Reset Link')) }}
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection