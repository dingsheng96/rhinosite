@extends('layouts.master', ['title' => '', 'guest_view' => true, 'body' => 'enduser'])

@section('content')

<div class="d-flex justify-content-center align-items-center three-quarter-height">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-body py-5 shadow-lg text-center">

                    <h4>Verification Link Expired</h4>

                    <br>

                    <div class="my-3">
                        <p class="text-muted">The email verification link has already expired. Please log into your account and click the resend button to request another.</p>
                    </div>

                    <div class="my-3">
                        <a role="button" href="{{ route('login') }}" class="btn btn-orange btn-lg">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection