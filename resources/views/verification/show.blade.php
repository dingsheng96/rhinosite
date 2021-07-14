@extends('layouts.master', ['title' => trans_choice('modules.verification', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.verification', 1)]) }}</h3>
                    <div class="card-tools">
                        <h4>{!! $verification->status_label !!}</h4>
                    </div>
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-5 col-md-3">
                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link active" id="vert-tabs-general-tab" data-toggle="pill" href="#vert-tabs-general" role="tab" aria-controls="vert-tabs-general" aria-selected="true">{{ __('labels.general') }}</a>
                                <a class="nav-link" id="vert-tabs-details-tab" data-toggle="pill" href="#vert-tabs-details" role="tab" aria-controls="vert-tabs-details" aria-selected="false">{{ __('labels.details') }}</a>
                            </div>
                        </div>
                        <div class="col-7 col-md-9">
                            <div class="tab-content" id="vert-tabs-tabContent">
                                <div class="tab-pane text-left fade show active" id="vert-tabs-general" role="tabpanel" aria-labelledby="vert-tabs-general-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="name" class="col-form-label">{{ __('labels.name') }}</label>
                                                <p class="form-control" id="name">{{ $verification->user->name ?? null }}</p>
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
                                                    <p class="form-control" id="phone">{{ $verification->user->phone ?? null }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="email" class="col-form-label">{{ __('labels.email') }}</label>
                                                <p class="form-control" id="email">{{ $verification->user->email ?? null }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="website" class="col-form-label">{{ __('labels.website') }}</label>
                                                <p class="form-control" id="website">{{ $verification->website ?? null }}</p>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="facebook" class="col-form-label">{{ __('labels.facebook') }}</label>
                                                <p class="form-control" id="facebook">{{ $verification->facebook ?? null }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="category" class="col-form-label">{{ __('labels.category') }}</label>
                                                <p class="form-control" id="category">{{ $verification->user->user_category->name ?? null }}</p>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="experience" class="col-form-label">{{ __('labels.years_of_experience') }}</label>
                                                <p class="form-control" id="experience">{{ $verification->years_of_experience ?? null }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="address" class="col-form-label">{{ __('labels.address') }}</label>
                                                <textarea id="address" cols="100" rows="5" class="form-control bg-white" disabled>{{ $verification->user->full_address }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane text-left fade show" id="vert-tabs-details" role="tabpanel" aria-labelledby="vert-tabs-details-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="pic_name" class="col-form-label">{{ __('labels.pic_name') }}</label>
                                                <p class="form-control" id="pic_name">{{ $verification->pic_name ?? null }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="pic_phone" class="col-form-label">{{ __('labels.pic_contact') }}</label>
                                                <p class="form-control" id="pic_phone">{{ $verification->pic_phone ?? null }}</p>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="pic_email" class="col-form-label">{{ __('labels.pic_email') }}</label>
                                                <p class="form-control" id="pic_email">{{ $verification->pic_email ?? null }}</p>
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
                                                        @forelse ($documents as $document)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>
                                                                <a href="{{ $document->full_file_path }}" target="_blank">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                    {{ $document->filename }}
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
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a href="{{ route('verifications.index') }}" role="button" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-chevron-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection