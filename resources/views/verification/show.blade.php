@extends('layouts.master', ['parent_title' => trans_choice('modules.user', 2), 'title' => trans_choice('modules.submodules.registration', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.submodules.registration', 1)]) }}</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name" class="col-form-label">{{ __('labels.name') }}</label>
                                <p class="form-control" id="name">{{ $registration->name ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="status" class="col-form-label">{{ __('labels.status') }}</label>
                                {!! $registration->status_label !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="mobile_no" class="col-form-label">{{ __('labels.mobile_no') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white">+</span>
                                    </div>
                                    <p class="form-control" id="mobile_no">{{ $registration->mobile_no ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="tel_no" class="col-form-label">{{ __('labels.tel_no') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white">+</span>
                                    </div>
                                    <p class="form-control" id="tel_no">{{ $registration->tel_no ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="email" class="col-form-label">{{ __('labels.email') }}</label>
                                <p class="form-control" id="email">{{ $registration->email ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="reg_no" class="col-form-label">{{ __('labels.reg_no') }}</label>
                                <p class="form-control" id="reg_no">{{ $registration->reg_no ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name" class="col-form-label">{{ __('labels.address_1') }}</label>
                                <p class="form-control" id="name">{{ $registration->address->address_1 ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="mobile_no" class="col-form-label">{{ __('labels.address_2') }}</label>
                                <p class="form-control" id="mobile_no">{{ $registration->address->address_2 ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="tel_no" class="col-form-label">{{ __('labels.postcode') }}</label>
                                <p class="form-control" id="mobile_no">{{ $registration->address->postcode ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name" class="col-form-label">{{ __('labels.city') }}</label>
                                <p class="form-control" id="name">{{ $registration->address->city ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="mobile_no" class="col-form-label">{{ __('labels.country_state') }}</label>
                                <p class="form-control" id="mobile_no">{{ $registration->address->city->countryState->name ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="tel_no" class="col-form-label">{{ __('labels.country') }}</label>
                                <p class="form-control" id="mobile_no">{{ $registration->address->city->country->name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label for="media">{{ __('labels.media') }}</label>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    @forelse ($registration->media as $media)
                                    <tr>
                                        <td>
                                            <img src="" alt="" class="img-thumbnail img-size-32">
                                        </td>
                                        <td>{{ $media->type ?? '-' }}</td>
                                        <td>
                                            <a href="{{ $media->full_file_path }}" class="btn btn-link" download>
                                                {{ __('labels.download') }}
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan='3' class="text-center">{{ __('labels.no_records') }}</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a href="{{ route('users.registrations.index') }}" role="button" class="btn btn-light">
                        <i class="fas fa-times"></i>
                        {{ __('labels.cancel') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection