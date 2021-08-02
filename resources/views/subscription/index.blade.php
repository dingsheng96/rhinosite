@extends('layouts.master', ['title' => trans_choice('modules.subscription', 2), 'guest_view' => true, 'body' => 'enduser'])

@section('content')

<div class="container" style="padding-top: 5rem; padding-bottom: 5rem;">

    <div class="row justify-content-center">

        <div class="col-12">
            <h2 class="text-center font-weight-bold">{{ __('labels.choose_plan') }}</h2>
            <hr>
        </div>

        @forelse ($plans as $plan)
        <div class="col-12 col-md-3 my-3 py-3">
            <div class="card shadow-lg h-100">
                <div class="card-body text-center">

                    <h4 class="font-weight-bold mt-3 mb-4">{{ $plan->name ?? $plan->product->name }}</h4>

                    <h2 class="font-weight-bold d-inline">{{ $plan->prices->first()->selling_price_with_currency }}</h2>

                    <p class="text-muted">{!! nl2br($plan->description ?? $plan->product->description) !!}</p>

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

                <div class="card-footer border-0 bg-transparent pt-3 pb-5 text-center">
                    @if ($user->userSubscriptions->first() && $user->active_subscription->subscribable->id == $plan->id)
                    <span class="btn btn-success btn-lg shadow" style="pointer-events: none; cursor: default;">
                        {{ strtoupper(__('labels.current_plan')) }}
                    </span>
                    @else
                    <form action="{{ route('subscriptions.store') }}" method="post" role="form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="plan" value="{{ json_encode(['id' => $plan->id, 'class' => get_class($plan)]) }}">
                        <button type="submit" class="btn btn-orange btn-lg shadow">
                            {{ strtoupper(__('labels.select')) }}
                        </button>
                    </form>
                    @endif
                </div>

            </div>
        </div>
        @empty
        <div class="col-12 my-3 py-3">
            <p>{{ __('messages.no_plan_available') }}</p>
        </div>
        @endforelse

    </div>

</div>

@endsection