@extends('admin.layouts.master', ['title' => trans_choice('modules.verification', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.edit', ['module' => trans_choice('modules.verification', 1)]) }}</h3>
                </div>

                <form action="{{ route('admin.verifications.update', ['verification' => $verification->id]) }}" method="post" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="card-body">

                        <div class="form-group row">
                            <label for="name" class="col-form-label col-sm-3">{{ __('labels.contractor_name') }}</label>
                            <div class="col-sm-9">
                                <span class="form-control-plaintext" id="name">{{ $verification->name ?? null }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-form-label col-sm-3">{{ __('labels.contact_no') }}</label>
                            <div class="col-sm-9">
                                <span class="form-control-plaintext" id="phone">{{ $verification->formatted_phone_number ?? null }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-form-label col-sm-3">{{ __('labels.email') }}</label>
                            <div class="col-sm-9">
                                <span class="form-control-plaintext" id="email">{{ $verification->email ?? null }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="reg_no" class="col-form-label col-sm-3">{{ __('labels.reg_no') }}</label>
                            <div class="col-sm-9">
                                <span class="form-control-plaintext" id="reg_no">{{ $verification->userDetail->reg_no ?? null }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="business_since" class="col-form-label col-sm-3">{{ __('labels.business_since') }}</label>
                            <div class="col-sm-9">
                                <span class="form-control-plaintext" id="business_since">{{ $verification->userDetail->business_since ?? null }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="col-form-label col-sm-3">{{ __('labels.address') }}</label>
                            <div class="col-sm-9">
                                <span id="address" class="form-control-plaintext">{{ $verification->address->full_address }}</span>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label for="website" class="col-form-label col-sm-3">{{ __('labels.website') }}</label>
                            <div class="col-sm-9">
                                <span class="form-control-plaintext" id="website">{{ $verification->userDetail->website ?? null }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="facebook" class="col-form-label col-sm-3">{{ __('labels.facebook') }}</label>
                            <div class="col-sm-9">
                                <span class="form-control-plaintext" id="facebook">{{ $verification->userDetail->facebook ?? null }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="whatsapp" class="col-form-label col-sm-3">{{ __('labels.whatsapp') }}</label>
                            <div class="col-sm-9">
                                <span class="form-control-plaintext" id="whatsapp">{{ $verification->userDetail->formatted_whatsapp ?? null }}</span>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label for="pic_name" class="col-form-label col-sm-3">{{ __('labels.pic_name') }}</label>
                            <div class="col-sm-9">
                                <span class="form-control-plaintext" id="pic_name">{{ $verification->userDetail->pic_name ?? null }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pic_phone" class="col-form-label col-sm-3">{{ __('labels.pic_contact') }}</label>
                            <div class="col-sm-9">
                                <span class="form-control-plaintext" id="pic_phone">{{ $verification->userDetail->formatted_pic_phone ?? null }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pic_email" class="col-form-label col-sm-3">{{ __('labels.pic_email') }}</label>
                            <div class="col-sm-9">
                                <span class="form-control-plaintext" id="pic_email">{{ $verification->userDetail->pic_email ?? null }}</span>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label for="media" class="col-form-label col-sm-3">{{ trans_choice('labels.document', 2) }}</label>
                            <div class="col-sm-9">
                                @include('admin.components.tbl_image', ['media' => $verification->media, 'action' => true])
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
                                    <label for="plan" class="col-form-label">{{ __('labels.free_trial_plan') }}</label>
                                    <select name="plan" id="plan" class="form-control select2 @error('plan') is-invalid @enderror">
                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.free_trial_plan'))]) }} ---</option>
                                        @foreach ($plans as $plan)
                                        <option value="{{ base64_encode(json_encode(['id' => $plan->id, 'class' => get_class($plan), 'trial' => $plan->trial_mode])) }}"
                                            {{ old('plan') == base64_encode(json_encode(['id' => $plan->id, 'class' => get_class($plan), 'trial' => $plan->trial_mode])) ? 'selected' : null }}>
                                            {{ $plan->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('plan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a href="{{ route('admin.verifications.index') }}" role="button" class="btn btn-light mx-2 btn-rounded-corner">
                            <i class="fas fa-caret-left"></i>
                            {{ __('labels.back') }}
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