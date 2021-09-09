@extends('admin.layouts.master', ['title' => trans_choice('modules.service', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.service', 1)]) }}</h3>
                </div>

                <div class="card-body">

                    <div class="form-group row">
                        <label for="name" class="col-form-label col-sm-3">{{ __('labels.name') }}</label>
                        <div class="col-sm-9">
                            <span class="form-control-plaintext" id="name">{{ $service->name }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-form-label col-sm-3">{{ __('labels.description') }}</label>
                        <div class="col-sm-9">
                            <span class="form-control-plaintext" id="description">{!! nl2br($service->description ?? '-') !!}</span>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a href="{{ route('admin.services.index') }}" role="button" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection