@extends('layouts.master', ['title' => '', 'guest_view' => true])

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card p-5">

                <img src="{{ ($status) ? asset('storage/check.png') : asset('storage/error.png') }}" alt="status" class="img-lg card-img-top mx-auto">

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h3 class="font-weight-bold {{ ($status) ? 'text-success' : 'text-danger' }}">{{ trans_choice('labels.payment_status', $status) }}</h3>
                            <h4>
                                {{ __('labels.order_no') }}
                                <br>
                                {{ $transaction->sourceable->order_no }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent text-center">

                    <a href="{{ route('dashboard') }}" role="button" class="btn btn-outline-primary btn-lg btn-rounded-corner">
                        {{ __('labels.return_dashboard') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection