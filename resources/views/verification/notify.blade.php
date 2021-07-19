@extends('layouts.master', ['title' => trans_choice('modules.merchant', 2), 'guest_view' => true])

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">

            <div class="card card-body">
                <img src="{{ asset('storage/verification.png') }}" alt="" class="card-img-top img-size-64 img-fluid mx-auto">

                <div class="row py-3">
                    <div class="col-12 text-center">
                        <h3>{{ __('labels.verify_in_progress') }}...</h3>

                        <h5 class="text-muted">
                            Thank you for submitting your company profile. Our admin will go through the details. <br> You may start posting once your account is being verified.
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection