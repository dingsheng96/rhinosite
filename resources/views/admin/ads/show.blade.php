@extends('admin.layouts.master', ['title' => __('modules.ads')])

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

                    <div class="form-group row">
                        <label for="project" class="col-form-label col-sm-3">{{ trans_choice('labels.project', 1) }}</label>
                        <div class="col-sm-9">
                            <span class="form-control-plaintext">{{ $project->title }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="merchant" class="col-form-label col-sm-3">{{ trans_choice('labels.merchant', 1) }}</label>
                        <div class="col-sm-9">
                            <span class="form-control-plaintext">{{ $project->user->name }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="ads_type" class="col-form-label col-sm-3">{{ __('labels.ads_type') }}</label>
                        <div class="col-sm-9">
                            <span class="form-control-plaintext">{{ $project->adsBoosters->first()->product->name }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant" class="col-form-label">{{ __('labels.ads_boosting_history') }}</label>
                        <div class="table-responsive">
                            <table class="table w-100 table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('#') }}</th>
                                        <th>{{ trans_choice('labels.boosted_at', 1) }}</th>
                                        <th>{{ __('labels.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($project->adsBoosters as $booster)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $booster->boosted_at }}</td>
                                        <td>{!! $booster->status_label !!}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3">{{ __('messages.no_records') }}</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ route('admin.ads-boosters.index') }}" class="btn btn-light mx-2 btn-rounded-corner">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>

            </div>
        </div>

    </div>

</div>
@endsection