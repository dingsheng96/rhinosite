@if ($errors->has('update.*'))

@push('scripts')

<script type="text/javascript">
    $(window).on('load', function () {
        $('#updatePriceModal').modal('show');
    });
</script>

@endpush

@endif

<div class="modal fade show hide" id="updatePriceModal" tabindex="-1" role="dialog" aria-labelledby="updatePriceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updatePriceModal">{{ __('modules.edit', ['module' => __('labels.price')]) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('ecommerce.products.attributes.prices.update', ['product' => $product->id, 'attribute' => $attribute->id, 'price' => '__REPLACE__']) }}" method="POST" role="form" enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="modal-body">
                    <div class="form-group">
                        <label for="update_currency" class="col-form-label">{{ __('labels.currency') }} <span class="text-red">*</span></label>
                        <select name="update[currency]" id="update_currency" class="form-control select2 @error('update.currency') is-invalid @enderror">
                            <option disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.currency'))]) }} ---</option>
                            @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}" {{ old('update.currency') == $currency->id ? 'selected' : null }}>{{ $currency->name . ' (' . $currency->code . ')' }}</option>
                            @endforeach
                        </select>
                        @error('update.currency')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="update_unit_price" class="col-form-label">{{ __('labels.unit_price') }}</label>
                                <input type="number" id="update_unit_price" name="update[unit_price]" value="{{ old('update.unit_price', '0.00') }}" class="form-control @error('update.unit_price') is-invalid @enderror" min="0.00" step="0.01">
                                @error('update.unit_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="update_discount" class="col-form-label">{{ __('labels.discount') }}</label>
                                <input type="number" id="update_discount" name="update[discount]" value="{{ old('update.discount', '0.00') }}" class="form-control @error('update.discount') is-invalid @enderror" min="0.00" step="0.01">
                                @error('update.discount')
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
                                <label for="update_discount_percentage">{{ __('labels.discount') . ' (%)' }}</label>
                                <p class="form-control" id="update_discount_percentage">0.00</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="update_selling_price">{{ __('labels.selling_price') }}</label>
                                <p class="form-control" id="update_selling_price">0.00</p>
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