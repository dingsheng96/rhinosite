@extends('layouts.master', ['title' => trans_choice('modules.member', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.member', 1)]) }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name" class="col-form-label">{{ __('labels.name') }}</label>
                                <p id="name" class="form-control">{{ $member->name ?? null }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="status" class="col-form-label">{{ __('labels.status') }}</label>
                                <h4>{!! $member->status_label !!}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="phone" class="col-form-label">{{ __('labels.contact_no') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white">+</span>
                                    </div>
                                    <p id="phone" class="form-control">{{ $member->phone ?? null }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="email" class="col-form-label">{{ __('labels.email') }}</label>
                                <p id="email" class="form-control">{{ $member->email ?? null }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="logo" class="col-form-label">{{ __('labels.profile_picture') }}</label>
                                <img src="{{ $member->logo->full_file_path ?? $default_preview }}" alt="preview" class="custom-img-preview img-thumbnail d-block mx-auto">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="address" class="col-form-label">{{ __('labels.address') }}</label>
                                <textarea id="address" cols="30" rows="8" class="form-control bg-white" disabled>{{ $member->address->full_address ?? null }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a href="{{ route('admin.members.index') }}" role="button" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-chevron-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection