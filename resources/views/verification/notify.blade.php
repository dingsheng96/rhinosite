@extends('layouts.master', ['title' => trans_choice('modules.merchant', 2), 'guest_view' => true, 'body' => 'enduser'])

@section('content')

<div class="d-flex justify-content-center align-items-center three-quarter-height">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-body shadow">
                    @if ($user->userDetail->status == 'pending')

                    <img src="{{ asset('storage/verification.png') }}" alt="" class="card-img-top img-fluid mx-auto" style="height: auto; width: 100px;">
                    <div class="row pt-4 pb-3">
                        <div class="col-12 text-center">
                            <h3>{{ __('app.verify_in_progress_title') }}...</h3>
                            <br>
                            <div class="row justify-content-center">
                                <p class="col-md-10">{!! __('app.verify_in_progress_message') !!}</p>
                            </div>
                        </div>
                    </div>

                    @elseif($user->userDetail->status == 'rejected')

                    <img src="{{ asset('storage/error.png') }}" alt="" class="card-img-top img-fluid mx-auto" style="height: auto; width: 100px;">
                    <div class="row pt-4 pb-3">
                        <div class="col-12 text-center">
                            <h3>{{ __('app.verify_rejected_title') }}</h3>
                            <br>
                            <div class="row justify-content-center">
                                <p class="col-md-10">{!! __('app.verify_rejected_message') !!}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent d-flex justify-content-center">
                        <a href="{{ route('verifications.resubmit') }}" role="button" class="btn btn-orange btn-lg">
                            {{ strtoupper(__('app.verify_rejected_btn_update')) }}
                        </a>
                    </div>

                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@endsection