@extends('admin.layouts.master', ['title' => __('modules.verify_email'), 'guest_view' => true, 'body' => 'enduser'])

@section('content')

<div class="d-flex justify-content-center align-items-center three-quarter-height">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-body py-5 shadow-lg text-center">

                    @if (session('resent'))
                    <div class="alert alert-success my-3" role="alert">
                        {{ __('app.fresh_verification_link') }}
                    </div>
                    @endif

                    <h4>{{ __('app.verify_email_address') }}</h4>

                    {{ __('app.verification_message') }}

                    <br>

                    <div class="my-3">
                        {{ __('app.resend_verification_email_message') }}
                    </div>

                    <div class="my-3">
                        <form method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-orange btn-lg">{{ __('app.btn_resend_verification_email') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection