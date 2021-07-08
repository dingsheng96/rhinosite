@extends('layouts.master', ['title' => trans_choice('modules.subscription', 2)])

@section('content')

<div class="container-fluid">

    @if (!empty($subscription))
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning bg-orange" role="alert">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <h4 class="alert-heading">{{ __('labels.current_plan') }}</h4>
                        <p class="font-weight-bold">
                            {{ trans_choice('labels.subscribed_at', 2, ['date' => $subscription->subscription_date]) }}
                        </p>
                    </div>
                    <div class="col-md-6 col-12 text-md-right">
                        <h4 class="alert-heading">{{ trans_choice('labels.month', $subscription->validity_in_month, ['value' => $subscription->validity_in_month])  }}</h4>
                        <p class="font-weight-bold">
                            {{ trans_choice('labels.expired_at', 2, ['date' => $subscription->expired_date]) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @includeWhen(!empty($plans), 'subscription.plan', ['plans' => $plans])

</div>

@endsection

@push('scripts')
<script src="{{ asset('js/cart.js?v='.time()) }}"></script>
@endpush