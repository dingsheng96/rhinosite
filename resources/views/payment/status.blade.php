@extends('layouts.master', ['title' => '', 'guest_view' => true, 'body' => 'enduser'])

@section('content')

<div class="container" style="padding-top: 5rem; padding-bottom: 5rem;">
    <div class="row">
        <div class="col-12">
            <div class="card card-body shadow-lg py-5">

                <img src="{{ ($status) ? asset('storage/check.png') : asset('storage/error.png') }}" alt="status" class="card-img-top img-fluid mx-auto" style="height: auto; width: 100px;">

                <div class="row my-5">
                    <div class="col-12 text-center">
                        <h3 class="font-weight-bold {{ ($status) ? 'text-success' : 'text-danger' }}">{{ trans_choice('labels.payment_status', $status) }}</h3>
                        <h4>
                            {{ __('labels.order_no') }}
                            <br>
                            {{ $transaction->sourceable->order_no }}
                        </h4>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <a href="{{ route('dashboard') }}" role="button" class="btn btn-lg btn-orange">
                        {{ strtoupper(__('labels.return_dashboard')) }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection