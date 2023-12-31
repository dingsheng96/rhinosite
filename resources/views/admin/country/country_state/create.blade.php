@if ($errors->has('create.*'))

@push('scripts')

<script type="text/javascript">
    $(window).on('load', function () {
        $('#countryStateModal').modal('show');
    });
</script>

@endpush

@endif

<div class="modal fade show hide" id="countryStateModal" tabindex="-1" role="dialog" aria-labelledby="countryStateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="countryStateModal">{{ __('modules.create', ['module' => trans_choice('modules.country_state', 1)]) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.countries.country-states.store', ['country' => $country->id]) }}" method="POST" role="form" enctype="multipart/form-data">
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

                    <h6 class="text-center">-- Or --</h6>

                    <div class="form-group">
                        <label for="file" class="col-form-label">{{ trans_choice('labels.upload_file', 1) }} <span class="text-red">*</span></label>
                        <div class="custom-file">
                            <input type="file" id="file" name="create[file]" class="custom-file-input @error('create.file') is-invalid @enderror">
                            <label class="custom-file-label" for="validatedCustomFile">Choose file</label>
                            @error('create.file')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="icheck-primary">
                            <input type="checkbox" name="create[withCity]" id="header" {{ old('create.withCity') ? 'checked' : null }}>
                            <label for="header">{{ __('labels.with_city') }}</label>
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