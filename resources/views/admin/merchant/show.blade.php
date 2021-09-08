@extends('admin.layouts.master', ['title' => trans_choice('modules.merchant', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.merchant', 1)]) }}</h3>
                </div>

                <div class="card-body">

                    <div class="form-group row">
                        <label for="name" class="col-form-label col-sm-2">{{ __('labels.contractor_name') }}</label>
                        <div class="col-sm-10">

                            <span id="name" class="form-control-plaintext">{{ $merchant->name ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-form-label col-sm-2">{{ __('labels.status') }}</label>
                        <div class="col-sm-10">

                            <span id="status" class="form-control-plaintext">{!! $merchant->status_label !!}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="phone" class="col-form-label col-sm-2">{{ __('labels.contact_no') }}</label>
                        <div class="col-sm-10">
                            <span id="phone" class="form-control-plaintext">{{ $merchant->formatted_phone_number ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-form-label col-sm-2">{{ __('labels.email') }}</label>
                        <div class="col-sm-10">

                            <span id="email" class="form-control-plaintext">{{ $merchant->email ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="website" class="col-form-label col-sm-2">{{ __('labels.website') }}</label>
                        <div class="col-sm-10">

                            <span id="website" class="form-control-plaintext">{{ $merchant->userDetail->website ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="facebook" class="col-form-label col-sm-2">{{ __('labels.facebook') }}</label>
                        <div class="col-sm-10">

                            <span id="facebook" class="form-control-plaintext">{{ $merchant->facebook ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="experience" class="col-form-label col-sm-2">{{ __('labels.business_since') }}</label>
                        <div class="col-sm-10">

                            <span id="experience" class="form-control-plaintext">{{ $merchant->userDetail->business_since ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address" class="col-form-label col-sm-2">{{ __('labels.address') }}</label>
                        <div class="col-sm-10">
                            <span id="address" class="form-control-plaintext">{{ $merchant->address->full_address ?? '-' }}</span>
                        </div>

                    </div>

                    <div class="form-group row">
                        <label for="logo" class="col-form-label col-sm-2">{{ __('labels.logo') }}</label>
                        <div class="col-sm-10">

                            <img src="{{ $merchant->logo->full_file_path ?? $default_preview }}" alt="preview" class="img-thumbnail" style="height: 200px; width: 200px; object-fit: contain;">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label for="pic_name" class="col-form-label col-sm-2">{{ __('labels.pic_name') }}</label>
                        <div class="col-sm-10">
                            <span id="pic_name" class="form-control-plaintext">{{ $merchant->userDetail->pic_name ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="pic_phone" class="col-form-label col-sm-2">{{ __('labels.pic_contact') }}</label>
                        <div class="col-sm-10">
                            <span id="pic_phone" class="form-control-plaintext">{{ $merchant->userDetail->formatted_pic_phone ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="pic_email" class="col-form-label col-sm-2">{{ __('labels.pic_email') }}</label>
                        <div class="col-sm-10">

                            <span id="pic_email" class="form-control-plaintext">{{ $merchant->userDetail->pic_email ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="media" class="col-form-label col-sm-2">{{ trans_choice('labels.document', 2) }}</label>
                        <div class="col-sm-10 table-responsive">
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
                                    @forelse ($merchant->media as $document)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $document->type }}</td>
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

                    <hr>

                    @if (!empty($subscription))
                    <div class="form-group row">
                        <label for="current_plan" class="col-form-label col-sm-2">{{ __('labels.current_plan') }}</label>
                        <div class="col-sm-10">
                            <span id="current_plan" class="form-control-plaintext">{{ $subscription->subscribable->name ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="subscribed_at" class="col-form-label col-sm-2">{{ trans_choice('labels.subscribed_at', 1) }}</label>
                        <div class="col-sm-10">
                            <span id="subscribed_at" class="form-control-plaintext">{{ $subscription->activated_at ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="subscribed_at" class="col-form-label col-sm-2">{{ trans_choice('labels.next_billing_at', 1) }}</label>
                        <div class="col-sm-10">
                            <span id="subscribed_at" class="form-control-plaintext">{{ $subscription->next_billing_at ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="terminated_at" class="col-form-label col-sm-2">{{ trans_choice('labels.terminated_at', 1) }}</label>
                        <div class="col-sm-10">
                            <span id="terminated_at" class="form-control-plaintext">{{ $subscription->terminated_at ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="renewed_at" class="col-form-label col-sm-2">{{ trans_choice('labels.renewed_at', 1) }}</label>
                        <div class="col-sm-10">
                            <span id="renewed_at" class="form-control-plaintext">{{ $subscription_log->renewed_at ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="subscribed_at" class="col-form-label col-sm-2">{{ trans_choice('labels.expired_at', 1) }}</label>
                        <div class="col-sm-10">
                            <span id="subscribed_at" class="form-control-plaintext">{{ $subscription_log->expired_at ?? '-' }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="subscription_history" class="col-form-label">{{ __('labels.subscription_history') }}</label>
                        <div class="col-12">
                            <div class="table-responsive">
                                {!! $dataTable->table() !!}
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a href="{{ route('admin.merchants.index') }}" role="button" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush