<div class="card">
    <img src="{{ asset($project->thumbnail->full_file_path) }}" alt="{{ $project->thumbnail->filename }}" class="card-img-top custom-thumbnail">
    <div class="card-body">
        <div class="d-flex align-items-start flex-column">
            <p class="card-title">{{ $project->english_title ?? null }}</p>
            <p class="card-title">{{ $project->chinese_title ?? null }}</p>
            <span class="mt-3">
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </span>
        </div>
    </div>
    <div class="card-footer bg-transparent text-right">
        @include('components.action', [
        'view' => [
        'permission' => 'project.read',
        'route' => route('projects.show', ['project' => $project->id])
        ],
        'update' => [
        'permission' => 'project.update',
        'route' => route('projects.edit', ['project' => $project->id])
        ],
        'delete' => [
        'permission' => 'project.delete',
        'route' => route('projects.destroy', ['project' => $project->id])
        ]
        ])
    </div>
</div>