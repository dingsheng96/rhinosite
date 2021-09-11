@extends('admin.layouts.master', ['title' => __('labels.user_account')])

@section('content')

<div class="container-fluid">

    <div class="row">

        <div class="col-12">
            <form action="{{ route('admin.account.store') }}" method="post" role="form" enctype="multipart/form-data">
                @csrf
                <div class="card shadow">

                    <div class="card-body">

                        <div class="form-group row">
                            <label for="name" class="col-form-label col-sm-2">{{ __('labels.name') }} <span class="text-red">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) ?? null }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-form-label col-sm-2">{{ __('labels.email') }} <span class="text-red">*</span></label>
                            <div class="col-sm-10">
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) ?? null }}" class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="status" class="col-form-label col-sm-2">{{ __('labels.status') }}</label>
                            <div class="col-sm-10">
                                {!! $user->status_label !!}
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label for="password" class="col-form-label col-sm-2">{{ __('labels.new_password') }}</label>
                            <div class="col-sm-10">
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                <p>* {{ __('messages.password_format') }}</p>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password_confirmation" class="col-form-label col-sm-2">{{ __('labels.new_password_confirmation') }}</label>
                            <div class="col-sm-10">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
                                @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
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