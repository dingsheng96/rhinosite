@extends('layouts.master', ['title' => __('labels.user_account')])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12 col-md-3">
            <div class="card card-secondary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{{ $user->logo->full_file_path ?? $default_preview }}" alt="profile">
                        <h3 class="profile-username">{{ $user->name }}</h3>
                        <p>{!! $user->status_label !!}</p>
                    </div>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <strong><i class="fas fa-clock mr-1 text-orange"></i> {{ __('labels.last_login_at') }}</strong>
                            <p class="text-muted">{{ $user->last_login_at->toDateTimeString() ?? '-' }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-9">
            <form action="{{ route('account.store') }}" method="post" role="form" enctype="multipart/form-data">
                @csrf
                <div class="card card-outline card-secondary">
                    <div class="card-header bg-transparent">
                        <h3 class="card-title">{{ __('labels.profile') }}</h3>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) ?? null }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="email" class="col-form-label">{{ __('labels.email') }} <span class="text-red">*</span></label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) ?? null }}" class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="new_password">{{ __('labels.new_password') }}</label>
                                    <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror">
                                    <p>* {{ __('messages.password_format') }}</p>
                                    @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="new_password_confirmation">{{ __('labels.new_password_confirmation') }}</label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control @error('new_password_confirmation') is-invalid @enderror">
                                    @error('new_password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <button type="submit" class="btn btn-outline-primary btn-rounded-corner float-right">
                            <i class="fas fa-paper-plane"></i>
                            {{ __('labels.submit') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    let datepicker = new Pikaday({
        field: $('.date-picker')[0],
        format: 'DD/MM/YYYY'
    });
</script>
@endpush