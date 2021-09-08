<div class="modal fade show hide" id="updateAdminModal" tabindex="-1" role="dialog" aria-labelledby="adminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateAdminModal">{{ __('modules.edit', ['module' => trans_choice('modules.admin', 1)]) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admins.update', ['admin' => '__REPLACE__']) }}" method="POST" role="form" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="update_name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                        <input type="text" id="update_name" name="update[name]" value="{{ old('update.name') }}" class="form-control ucfirst @error('update.name') is-invalid @enderror">
                        @error('update.name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="update_email" class="col-form-label">{{ __('labels.email') }} <span class="text-red">*</span></label>
                        <input type="email" id="update_email" name="update[email]" value="{{ old('update.email') }}" class="form-control @error('update.email') is-invalid @enderror">
                        @error('update.email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="col-form-label">{{ __('labels.status') }} <span class="text-red">*</span></label>
                        <select name="update[status]" id="update_status" class="form-control select2 @error('update.status') is-invalid @enderror">
                            @foreach ($statuses as $index => $status)
                            <option value="{{ $index }}" {{ old('update.status', 'active') == $index ? 'selected' : null }}>{{ $status}}</option>
                            @endforeach
                        </select>
                        @error('update.status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <hr>

                    <div class="form-group">
                        <label for="password" class="col-form-label">{{ __('labels.new_password') }}</label>
                        <input type="password" id="update_password" name="update[password]" value="{{ old('update.password') }}" class="form-control @error('update.password') is-invalid @enderror">
                        @error('update.password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <small>* <strong>{{ __('messages.password_format') }}</strong></small>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="col-form-label">{{ __('labels.new_password_confirmation') }}</label>
                        <input type="password" id="update_password_confirmation" name="update[password_confirmation]" value="{{ old('update.password_confirmation') }}" class="form-control @error('update.password_confirmation') is-invalid @enderror">
                        @error('update.password_confirmation')
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