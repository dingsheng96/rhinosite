@extends('layouts.master', ['title' => trans_choice('modules.project', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('labels.show_records', ['total' => $projects->total(), 'item' => $projects->count()]) }}</h3>
                    <div class="card-tools">
                        @can('project.create')
                        <a href="{{ route('projects.create') }}" class="btn btn-primary float-md-right btn-rounded-corner">
                            <i class="fas fa-plus"></i>
                            {{ __('modules.create', ['module' => trans_choice('modules.project', 1)]) }}
                        </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    @forelse ($projects as $project)
                    @if ($loop->first || $loop->iteration % 3 == 1)
                    <div class="row py-md-3">
                        <div class="col-12">
                            <div class="card-deck">
                                @endif

                                <div class="card">
                                    <img class="card-img-top" src="..." alt="Card image cap">
                                    <div class="card-body">
                                        @foreach ($project->translations as $title)
                                        <h5 class="card-title">{{ $title->value ?? '-' }}</h5>
                                        @endforeach
                                        <h4 class="card-text font-weight-bold">{{ $project->price ?? '-' }}</h4>
                                        <p class="card-text"><small class="text-muted">{{ $project->location ?? '-' }}</small></p>
                                    </div>
                                    <div class="card-footer bg-transparent text-md-right text-center">
                                        @canany(['project.read', 'project.update', 'project.delete'])
                                        @include('components.action', [
                                        'view' => [
                                        'permission' => 'project.read',
                                        'route' => route('projects.show', ['project' => $project->id])
                                        ],
                                        'update' => [
                                        'permission' => 'project.update',
                                        'route' => route('projects.edit', ['project' => $project->id]),
                                        ],
                                        'delete' => [
                                        'permission' => 'project.delete',
                                        'route' => route('projects.destroy', ['project' => $project->id])
                                        ]
                                        ])
                                        @endcanany
                                    </div>
                                </div>

                                @if($loop->last || $loop->iteration % 3 == 0)

                                @if ($projects->count() != $projects->perPage() && $loop->last % 3 == 1)
                                @for ($i = 3; $i > $loop->iteration % 3 ; $i--)
                                <div class="card invisible"></div>
                                @endfor
                                @endif

                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <p class="text-center">{{ __('labels.empty_list', ['list' => strtolower(trans_choice('modules.project', 2))]) }}</p>
                    @endforelse
                </div>

                @if ($projects->count() > 0)
                <div class="card-footer bg-transparent d-flex justify-content-md-end justify-content-center">
                    {!! $projects->links() !!}
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection