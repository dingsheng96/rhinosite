@extends('layouts.master', ['title' => trans_choice('modules.subscription', 2), 'guest_view' => true])

@section('content')

<div class="container" style="padding-top: 7rem; padding-bottom: 5rem;">

    <h1 class="text-center font-weight-bold my-5">
        {{ __('labels.monthly_subscription_plan') }}
    </h1>

    <div class="row py-3">
        @foreach ($plans as $plan)
        <div class="col-12 col-md-4 my-3">
            <div class="card shadow">
                <div class="card-body text-center">

                    <h3 class="font-weight-bold mt-3 mb-4">{{ $plan->name ?? $plan->product->name }}</h3>

                    <h2 class="font-weight-bold d-inline">{{ $plan->prices->first()->selling_price_with_currency }}</h2>

                    <p class="text-muted">
                        {!! $plan->price_per_validity_type ? '('.trans_choice('labels.month', 1, ['value' => $plan->price_per_validity_type . ' /']).')' : '&nbsp;' !!}
                    </p>

                    <p>{!! nl2br($plan->description) !!}</p>

                    @if (optional($plan->products)->count() > 0)
                    <div class="pt-3">
                        <p class="mb-0 font-weight-bold">{{ __('labels.package_include') }} :</p>
                        <ul class="list-unstyled pl-0">
                            @forelse ($plan->products as $item)
                            <li class="my-3">
                                {{ $item->product->name }}
                                <span class="mx-1">( {{ 'x' . $item->pivot->quantity }} )</span>
                            </li>
                            @empty
                            @endforelse
                        </ul>
                    </div>
                    @endif

                </div>

                <div class="card-footer border-0 bg-transparent py-5">
                    @if ($user->active_subscription->subscribable->id == $plan->id)
                    <span class="btn btn-success btn-lg btn-block" style="pointer-events: none; cursor: default;">
                        {{ strtoupper(__('labels.current_plan')) }}
                    </span>
                    @else
                    <form action="{{ route('subscriptions.store') }}" method="post" role="form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="plan" value="{{ json_encode(['id' => $plan->id, 'class' => get_class($plan)]) }}">
                        <button type="submit" class="btn btn-orange btn-lg btn-block">
                            {{ strtoupper(__('labels.select')) }}
                        </button>
                    </form>
                    @endif
                </div>

            </div>
        </div>
        @endforeach
    </div>

</div>

@endsection