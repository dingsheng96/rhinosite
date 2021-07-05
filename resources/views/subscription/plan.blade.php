@if (empty($plans))

@else

<div class="multiple-items-slide row">
    @foreach ($plans as $plan)
    <div class="col-md-3 col-12">
        <a href="#" class="btn-add-package" data-item="{{ $plan->id }}">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="font-weight-bold">{{ $plan->name }}</h4>
                    <h2 class="font-weight-bold">{{ $plan->price->selling_price }}</h2>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

<form action="{{ route('ecommerce.carts.store') }}" method="POST" id="packageForm" class="d-none" enctype="multipart/form-data" role="form">
    @csrf
    <input type="hidden" name="item[type]" value="package">
    <input type="hidden" name="item[action]" value="add">
    <input type="hidden" name="item[quantity]" value="1">
    <input type="hidden" name="item[item_id]">
</form>

@endif