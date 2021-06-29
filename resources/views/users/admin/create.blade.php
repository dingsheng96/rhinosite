@if ($errors->has('create.*'))

@push('scripts')

<script type="text/javascript">
    $(window).on('load', function () {
        $('#adminModal').modal('show');
    });
</script>

@endpush

@endif

<div class="modal fade show hide" id="adminModal" tabindex="-1" role="dialog" aria-labelledby="adminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminModal">{{ __('modules.create', ['module' => trans_choice('modules.submodules.admin', 1)]) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('users.admins.store') }}" method="POST" role="form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="create_name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                        <input type="text" id="create_name" name="create[name]" value="{{ old('create.name') }}" class="form-control ucfirst @error('create.name') is-invalid @enderror" required>
                        @error('create.name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="create_email" class="col-form-label">{{ __('labels.email') }} <span class="text-red">*</span></label>
                        <input type="email" id="create_email" name="create[email]" value="{{ old('create.email') }}" class="form-control @error('create.email') is-invalid @enderror" required>
                        @error('create.email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="col-form-label">{{ __('labels.status') }} <span class="text-red">*</span></label>
                        <select name="create[status]" id="create_status" class="form-control select2 @error('create.status') is-invalid @enderror">
                            @foreach ($statuses as $index => $status)
                            <option value="{{ $index }}" {{ old('create.status', 'active') == $index ? 'selected' : null }}>{{ $status}}</option>
                            @endforeach
                        </select>
                        @error('create.status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-form-label">{{ __('labels.password') }} <span class="text-red">*</span></label>
                        <input type="password" id="password" name="create[password]" value="{{ old('create.password') }}" class="form-control @error('create.password') is-invalid @enderror" required>
                        @error('create.password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <small>* <strong>{{ __('labels.password_format') }}</strong></small>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="col-form-label">{{ __('labels.password_confirmation') }} <span class="text-red">*</span></label>
                        <input type="password" id="password_confirmation" name="create[password_confirmation]" value="{{ old('create.password_confirmation') }}" class="form-control @error('create.password_confirmation') is-invalid @enderror">
                        @error('create.password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
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