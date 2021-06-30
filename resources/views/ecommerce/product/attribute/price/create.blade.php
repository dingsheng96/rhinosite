@if ($errors->has('create.*'))

@push('scripts')

<script type="text/javascript">
    $(window).on('load', function () {
        $('#createPriceModal').modal('show');
    });
</script>

@endpush

@endif

<div class="modal fade show hide" id="createPriceModal" tabindex="-1" role="dialog" aria-labelledby="createPriceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPriceModal">{{ __('modules.create', ['module' => __('labels.price')]) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('ecommerce.products.attributes.prices.store', ['product' => $product->id, 'attribute' => $attribute->id]) }}" method="POST" role="form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="currency_create" class="col-form-label">{{ __('labels.currency') }} <span class="text-red">*</span></label>
                        <select name="create[currency]" id="currency_create" class="form-control select2 @error('create.currency') is-invalid @enderror">
                            <option disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.currency'))]) }} ---</option>
                            @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}" {{ old('create.currency') == $currency->id ? 'selected' : null }}>{{ $currency->name . ' (' . $currency->code . ')' }}</option>
                            @endforeach
                        </select>
                        @error('create.currency')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="create_unit_price" class="col-form-label">{{ __('labels.unit_price') }}</label>
                                <input type="number" id="create_unit_price" name="create[unit_price]" value="{{ old('create.unit_price', '0.00') }}" class="form-control @error('create.unit_price') is-invalid @enderror" min="0.00" step="0.01">
                                @error('create.unit_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="create_discount" class="col-form-label">{{ __('labels.discount') }}</label>
                                <input type="number" id="create_discount" name="create[discount]" value="{{ old('create.discount', '0.00') }}" class="form-control @error('create.discount') is-invalid @enderror" min="0.00" step="0.01">
                                @error('create.discount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="create_discount_percentage">{{ __('labels.discount') . ' (%)' }}</label>
                                <p class="form-control" id="create_discount_percentage">0.00</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="create_selling_price">{{ __('labels.selling_price') }}</label>
                                <p class="form-control" id="create_selling_price">0.00</p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-rounded-corner" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>
                        {{ __('labels.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-outline-primary btn-rounded-corner">
                        <i class="fas fa-paper-plane mr-2"></i>
                        {{ __('labels.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>