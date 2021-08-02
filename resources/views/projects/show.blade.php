@extends('layouts.master', ['title' => trans_choice('modules.project', 2)])

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-transparent">
                <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.project', 1)]) }}</h3>
            </div>
            <div class="card-body">
                Coming soon...
            </div>
        </div>
    </div>
</div>

@endsection