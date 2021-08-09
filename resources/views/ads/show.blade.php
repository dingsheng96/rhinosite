@extends('layouts.master', ['title' => __('modules.ads')])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">
                        {{ __('modules.view', ['module' => __('modules.ads')]) }}
                    </h3>
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="project" class="col-form-label">{{ trans_choice('labels.project', 1) }}</label>
                                <p class="form-control">{{ $ads_booster->english_title }}</p>
                            </div>
                        </div>
                    </div>

                    @admin
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="merchant" class="col-form-label">{{ trans_choice('labels.merchant', 1) }}</label>
                                <p class="form-control">{{ $ads_booster->user->name }}</p>
                            </div>
                        </div>
                    </div>
                    @endadmin

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="merchant" class="col-form-label">{{ __('labels.ads_boosting_history') }}</label>
                                <hr>
                                {!! $dataTable->table() !!}
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ route('ads-boosters.index') }}" class="btn btn-light mx-2 btn-rounded-corner">
                        <i class="fas fa-chevron-left"></i>
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