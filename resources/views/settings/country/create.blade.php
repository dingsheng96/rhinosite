@if ($errors->has('create.*'))

@push('scripts')

<script type="text/javascript">
    $(window).on('load', function () {
        $('#countryModal').modal('show');
    });
</script>

@endpush

@endif

<div class="modal fade show hide" id="countryModal" tabindex="-1" role="dialog" aria-labelledby="countryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="countryModal">{{ __('modules.create', ['module' => trans_choice('modules.submodules.country', 1)]) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('settings.countries.store') }}" method="POST" role="form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                        <input type="text" id="name" name="create[name]" value="{{ old('create.name') }}" class="form-control ucfirst @error('create.name') is-invalid @enderror">
                        @error('create.name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="currency" class="col-form-label">{{ __('labels.currency') }} <span class="text-red">*</span></label>
                        <select name="create[currency]" id="currency" class="form-control select2 @error('create.currency') is-invalid @enderror">
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
                    <div class="form-group">
                        <label for="dial" class="col-form-label">{{ __('labels.dial_code') }} <span class="text-red">*</span></label>
                        <input type="text" id="dial" name="create[dial]" value="{{ old('create.dial') }}" class="form-control @error('create.dial') is-invalid @enderror" placeholder="Eg: 60, 61">
                        @error('create.dial')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>
                        {{ __('labels.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-paper-plane mr-2"></i>
                        {{ __('labels.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>