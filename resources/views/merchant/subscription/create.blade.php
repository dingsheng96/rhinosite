@extends('admin.layouts.master', ['title' => trans_choice('modules.subscription', 1)])

@section('content')

<div class="container-fluid">
    @csrf

    <div class="row">
        <div class="col-12">
            <div class="card shadow">

                <div class="card-header">
                    <h3 class="card-title">
                        {{ __('messages.offline_subscription') }}
                    </h3>
                </div>

                <form action="{{ route('admin.subscriptions.store') }}" method="post" enctype="multipart/form-data" role="form">
                    @csrf

                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="merchant">{{ __('labels.merchant') }} <span class="text-red">*</span></label>
                                    <select name="merchant" id="merchant" class="form-control select2 @error('merchant') is-invalid @enderror">
                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.merchant'))]) }} ---</option>
                                        @foreach ($merchants as $merchant)
                                        <option value="{{ $merchant->id }}" {{ old('merchant') == $merchant->id ? 'selected' : null }}>{{ $merchant->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('merchant')
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
                                    <label for="plan">{{ __('labels.plan') }} <span class="text-red">*</span></label>
                                    <select name="plan" id="plan" class="form-control select2 @error('plan') is-invalid @enderror">
                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.plan'))]) }} ---</option>
                                        @forelse ($plans as $plan)
                                        <option value="{{ json_encode(['id' => $plan->id, 'class' => get_class($plan), 'trial' => $plan->trial_mode]) }}" {{ old('plan') == json_encode(['id' => $plan->id, 'class' => get_class($plan), 'trial' => $plan->trial_mode]) ? 'selected' : null }}>
                                            {{ $plan->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('plan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="trans_no">{{ __('labels.transaction_no') }}</label>
                                <input type="text" name="trans_no" id="trans_no" value="{{ old('trans_no') }}" class="form-control @error('trans_no') is-invalid @enderror">
                                @error('trans_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <small>* {{ __('messages.trans_no_on_receipt') }}</small>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="payment_method">{{ __('labels.payment_method') }}</label>
                                <select name="payment_method" id="payment_method" class="form-control select2 @error('payment_method') is-invalid @enderror">
                                    <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.payment_method'))]) }} ---</option>
                                    @foreach ($payment_methods as $method)
                                    <option value="{{ $method->id }}" {{ old('payment_method') == $method->id ? 'selected' : null }}>{{ $method->name }}</option>
                                    @endforeach
                                </select>
                                @error('payment_method')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
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