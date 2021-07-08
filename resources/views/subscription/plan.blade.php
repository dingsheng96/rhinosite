<div class="row">
    <div class="col-12">
        <div class="card p-xl-5 p-3">

            <h3 class="text-center font-weight-bold mb-3">{{ __('labels.available_packages') }}</h3>

            <div class="multiple-items-slide row">
                @foreach ($plans as $plan)
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card card-orange card-outline h-100 py-xl-5">
                        <div class="card-body text-center">
                            <p class="font-weight-bold h4">{{ $plan->name }}</p>
                            <p>
                                <h2 class="font-weight-bold d-inline">{{ $plan->selling_price_with_currency }}</h2>
                                / {{ trans_choice('labels.month', 1, ['value' => null]) }}
                            </p>
                            <p>{!! nl2br($plan->description) !!}</p>

                            <div class="pt-3">
                                <p class="mb-0 font-weight-bold">{{ __('labels.package_include') }} :</p>
                                <ul class="package-list">
                                    @forelse ($plan->products->all() as $item)
                                    <li class="package-list-item">
                                        {{ $item->product->name }}
                                        <span class="ml-1">( {{ 'x' . $item->pivot->quantity }} )</span>
                                    </li>
                                    @empty
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent">
                            <form action="{{ route('subscriptions.purchase', ['subscription' => $plan->id]) }}" method="post" role="form" enctype="multipart/form-data">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-lg btn-block">
                                    {{ __('labels.select') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>