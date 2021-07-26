@extends('layouts.master', ['title' => __('modules.verify_email'), 'guest_view' => true])

@section('content')

<div class="d-flex justify-content-center align-items-center three-quarter-height">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-header bg-transparent">
                        <h4 class="text-center">{{ __('app.verify_email_address') }}</h4>
                    </div>

                    <div class="card-body text-center">
                        @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('app.fresh_verification_link') }}
                        </div>
                        @endif

                        {{ __('app.verification_message') }}

                        <br>

                        <div class="my-3">
                            {{ __('app.resend_verification_email_message') }}
                        </div>
                    </div>

                    <div class="card-footer bg-transparent text-center">
                        <form method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">{{ __('app.btn_resend_verification_email') }}</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>


@endsection