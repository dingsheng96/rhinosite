@extends('layouts.master', ['title' => trans_choice('modules.verification', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.edit', ['module' => trans_choice('modules.verification', 1)]) }}</h3>
                </div>

                <form action="{{ route('verifications.update', ['verification' => $verification->id]) }}" method="post" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('put')

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
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="service" class="col-form-label">{{ __('labels.service') }}</label>
                                    <p class="form-control" id="service">{{ $verification->userDetail->service->name ?? null }}</p>
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
                                    <textarea id="address" cols="100" rows="5" class="form-control bg-white" disabled>{{ $verification->address->full_address }}</textarea>
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
                                                <th style="width: 15%;">{{ __('labels.type') }}</th>
                                                <th style="width: 55%;">{{ __('labels.filename') }}</th>
                                                <th style="width: 20%">{{ __('labels.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($verification->media as $document)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ strtoupper($document->type) }}</td>
                                                <td>
                                                    <a href="{{ $document->full_file_path }}" target="_blank">
                                                        <i class="fas fa-external-link-alt"></i>
                                                        {{ $document->original_filename }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @include('components.action', [
                                                    'download' => [
                                                    'route' => $document->full_file_path,
                                                    'attribute' => 'download'
                                                    ]
                                                    ])
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center">{{ __('messages.no_records') }}</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="font-weight-bold">{{ __('labels.admin_section') }}</h5>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="status" class="col-form-label">{{ __('labels.status') }} <span class="text-red">*</span></label>
                                    <select name="status" id="status" class="form-control select2 @error('status') is-invalid @enderror">
                                        @foreach ($statuses as $index => $status)
                                        <option value="{{ $index }}" {{ old('status', $verification->userDetail->status) == $index ? 'selected' : null }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="trial" class="col-form-label">{{ __('labels.free_trial_plan') }}</label>
                                    <select name="trial" id="trial" class="form-control select2 @error('trial') is-invalid @enderror">
                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.free_trial_plan'))]) }} ---</option>
                                        @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ old('trial') == $plan->id ? 'selected' : null }}>{{ $plan->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('trial')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a href="{{ route('verifications.index') }}" role="button" class="btn btn-light mx-2 btn-rounded-corner">
                            <i class="fas fa-times"></i>
                            {{ __('labels.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-outline-primary btn-rounded-corner">
                            <i class="fas fa-paper-plane"></i>
                            {{ __('labels.submit') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection