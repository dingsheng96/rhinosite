@extends('layouts.master', ['title' => trans_choice('modules.verification', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.verification', 1)]) }}</h3>
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name" class="col-form-label">{{ __('labels.name') }}</label>
                                <p class="form-control" id="name">{{ $verification->name ?? null }}</p>
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
                                    <p class="form-control" id="phone">{{ $verification->phone ?? null }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="email" class="col-form-label">{{ __('labels.email') }}</label>
                                <p class="form-control" id="email">{{ $verification->email ?? null }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="reg_no" class="col-form-label">{{ __('labels.reg_no') }}</label>
                                <p class="form-control" id="reg_no">{{ $verification->userDetail->reg_no ?? null }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="business_since" class="col-form-label">{{ __('labels.business_since') }}</label>
                                <p class="form-control" id="business_since">{{ $verification->userDetail->business_since ?? null }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="website" class="col-form-label">{{ __('labels.website') }}</label>
                                <p class="form-control" id="website">{{ $verification->userDetail->website ?? null }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="facebook" class="col-form-label">{{ __('labels.facebook') }}</label>
                                <p class="form-control" id="facebook">{{ $verification->userDetail->facebook ?? null }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="whatsapp" class="col-form-label">{{ __('labels.whatsapp') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white">+</span>
                                    </div>
                                    <p class="form-control" id="whatsapp">{{ $verification->userDetail->whatsapp ?? null }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="address" class="col-form-label">{{ __('labels.address') }}</label>
                                <textarea id="address" cols="100" rows="5" class="form-control bg-white" disabled>{{ $verification->full_address }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="pic_name" class="col-form-label">{{ __('labels.pic_name') }}</label>
                                <p class="form-control" id="pic_name">{{ $verification->userDetail->pic_name ?? null }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="pic_phone" class="col-form-label">{{ __('labels.pic_contact') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white">+</span>
                                    </div>
                                    <p class="form-control" id="pic_phone">{{ $verification->userDetail->pic_phone ?? null }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="pic_email" class="col-form-label">{{ __('labels.pic_email') }}</label>
                                <p class="form-control" id="pic_email">{{ $verification->userDetail->pic_email ?? null }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group table-responsive">
                                <label for="media">{{ trans_choice('labels.document', 2) }}</label>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%;">{{ __('#') }}</th>
                                            <th style="width: 70%;">{{ __('labels.filename') }}</th>
                                            <th style="width: 20%">{{ __('labels.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($verification->media as $document)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ $document->full_file_path }}" target="_blank">
                                                    <i class="fas fa-external-link-alt"></i>
                                                    {{ $document->original_filename }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ $document->full_file_path }}" class="btn btn-link" download>
                                                    <i class="fas fa-download"></i>
                                                    {{ __('labels.download') }}
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">{{ __('messages.no_records') }}</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a href="{{ route('verifications.index') }}" role="button" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-times"></i>
                        {{ __('labels.cancel') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection