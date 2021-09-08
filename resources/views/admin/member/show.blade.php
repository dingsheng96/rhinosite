@extends('admin.layouts.master', ['title' => trans_choice('modules.member', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.member', 1)]) }}</h3>
                </div>
                <div class="card-body">

                    <div class="form-group row">
                        <label for="name" class="col-form-label col-sm-2">{{ __('labels.name') }}</label>
                        <div class="col-sm-10">
                            <span id="name" class="form-control-plaintext">{{ $member->name ?? null }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="phone" class="col-form-label col-sm-2">{{ __('labels.contact_no') }}</label>
                        <div class="col-sm-10">
                            <span id="phone" class="form-control-plaintext">{{ $member->formatted_phone_number ?? null }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-form-label col-sm-2">{{ __('labels.email') }}</label>
                        <div class="col-sm-10">
                            <span id="email" class="form-control-plaintext">{{ $member->email ?? null }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-form-label col-sm-2">{{ __('labels.status') }}</label>
                        <div class="col-sm-10">
                            <span id="status" class="form-control-plaintext">{!! $member->status_label !!}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address" class="col-form-label col-sm-2">{{ __('labels.address') }}</label>
                        <div class="col-sm-10">
                            <span id="address" class="form-control-plaintext">{{ $member->address->full_address ?? null }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="logo" class="col-form-label col-sm-2">{{ __('labels.profile_picture') }}</label>
                        <div class="col-sm-10">
                            <img src="{{ $member->logo->full_file_path ?? $default_preview }}" alt="preview" class="img-thumbnail" style="height: 200px; width: 200px; object-fit: contain;">
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a href="{{ route('admin.members.index') }}" role="button" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection