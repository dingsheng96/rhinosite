@push('scripts')
@if ($errors->has('update.*'))
<script type="text/javascript">
    $(window).on('load', function () {
        $('#updateCurrencyModal').modal('show');
    });
</script>
@endif
@endpush


<div class="modal fade show hide" id="updateCurrencyModal" tabindex="-1" role="dialog" aria-labelledby="updateCurrencyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateCurrencyModal">{{ __('modules.edit', ['module' => trans_choice('modules.submodules.currency', 1)]) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('settings.currencies.update', '__REPLACE__') }}" method="POST" role="form" enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="modal-body">
                    <div class="form-group">
                        <label for="update_name">{{ __('labels.name') }}</label>
                        <input type="text" id="update_name" name="update[name]" class="form-control ucfirst @error('update.name') is-invalid @enderror">
                        @error('update.name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="update_code">{{ __('labels.code') }}</label>
                        <input type="text" id="update_code" name="update[code]" class="form-control ucall @error('update.code') is-invalid @enderror">
                        @error('update.code')
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