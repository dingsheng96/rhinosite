@extends('layouts.master', ['title' => trans_choice('modules.project', 2)])

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-transparent">
                <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.project', 1)]) }}</h3>
                <div class="card-tools">
                    @can('project.update')
                    <a href="{{ route('projects.edit', ['project' => $project->id]) }}" class="btn btn-outline-primary float-md-right btn-rounded-corner">
                        <i class="fas fa-edit"></i>
                        {{ __('modules.edit', ['module' => trans_choice('modules.project', 1)]) }}
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                Coming soon. Waiting for front end
            </div>
        </div>
    </div>
</div>

@endsection