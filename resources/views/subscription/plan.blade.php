@if (empty($plans))

@else

<div class="multiple-items-slide row">
    @foreach ($plans as $plan)
    <div class="col-md-3 col-12">
        <div class="card subscription-card" data-object="{{ json_encode(['id' => $plan->id, 'type' => 'package', 'action' => 'add']) }}" style="cursor: pointer; ">
            <div class="card-body text-center">
                <h4 class="font-weight-bold">{{ $plan->name }}</h4>
                <h2 class="font-weight-bold">{{ $plan->price->selling_price }}</h2>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endif