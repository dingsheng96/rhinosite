@if ($errors->has('update.*'))

@push('scripts')

<script type="text/javascript">
    $(window).on('load', function () {
        $('#updateCategoryModal').modal('show');
    });
</script>

@endpush

@endif

<div class="modal fade show hide" id="updateCategoryModal" tabindex="-1" role="dialog" aria-labelledby="updateCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateCategoryModal">{{ __('modules.edit', ['module' => trans_choice('modules.submodules.category', 1)]) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('settings.categories.update', '__REPLACE__') }}" method="POST" role="form" enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                        <input type="text" id="update_name" name="update[name]" value="{{ old('update.name') }}" class="form-control ucfirst @error('update.name') is-invalid @enderror">
                        @error('update.name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-form-label">{{ __('labels.description') }}</label>
                        <textarea type="text" id="update_description" name="update[description]" class="form-control @error('update.description') is-invalid @enderror" cols="100" rows="5">{{ old('update.description') }}</textarea>
                        @error('update.description')
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