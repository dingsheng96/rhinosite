@extends('admin.layouts.master', ['title' => __('modules.dashboard')])

@section('content')

<div class="container-fluid">
    {{-- Widgets --}}
    <div class="row mb-4">

        <div class="col-lg-3 col-12">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $total_contractors ?? 0 }}</h3>
                    <p>{{ __('labels.total_contractors') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-store"></i>
                </div>
                <a href="{{ route('admin.merchants.index') }}" class="small-box-footer">{{ __('labels.more_info') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-12">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $total_members ?? 0 }}</h3>
                    <p>{{ __('labels.total_members') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user"></i>
                </div>
                <a href="{{ route('admin.members.index') }}" class="small-box-footer">{{ __('labels.more_info') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-12">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{ $weekly_transaction_amount ?? 0 }}</h3>
                    <p>{{ __('labels.total_weekly_transaction') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-random"></i>
                </div>
                <a href="{{ route('admin.transactions.index') }}" class="small-box-footer">{{ __('labels.more_info') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-12">
            <div class="small-box bg-orange">
                <div class="inner">
                    <h3>{{ $total_listing_projects ?? 0 }}</h3>
                    <p>{{ __('labels.total_listing_projects') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <a href="{{ route('admin.projects.index') }}" class="small-box-footer">{{ __('labels.more_info') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h3 class="card-title">{{ __('labels.monthly_sales') }}</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="salesChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h3 class="card-title">{{ __('labels.monthly_project_listing') }}</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="projectListingChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-body px-0 bg-transparent shadow-none">

                <div class="row">
                    <div class="col-12">
                        <h5 class="font-weight-bold d-inline">{{ __('labels.current_boosting_projects') }}</h5>
                        <a href="{{ route('admin.ads-boosters.index') }}" class="float-right">{{ __('labels.view_all') . ' >' }}</a>
                    </div>
                </div>

                <hr>

                <div class="row">
                    @forelse ($current_boosting_projects as $project)
                    <div class="col-md-3 col-12">
                        <a href="{{ route('admin.projects.show', ['project' => $project->id]) }}">
                            <div class="card shadow-lg border h-100">
                                <img src="{{ $project->thumbnail->full_file_path ?? $default_preview }}" alt="image" class="card-img-top" style="height: 250px; width: 100%; min-height: 210px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold text-secondary">{{ $project->english_title ?? '-' }}</h5>
                                    <p class="card-title">{{ $project->user->name }}</p>
                                    <p class="card-text">
                                        <span class="badge badge-pill badge-info badge-padding">{{ $project->user->service->name ?? '-' }}</span>
                                    </p>
                                </div>
                                <div class="card-footer bg-white">
                                    <p class="card-text text-muted"><i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $project->location ?? '-' }}</p>
                                </div>
                                <div class="card-footer bg-white">
                                    @foreach ($project->adsBoosters()->boosting()->get() as $booster)
                                    <span class="badge badge-pill badge-light {{ $booster->badge_color }}">{{ $booster->product->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="text-muted">{{ __('messages.no_records') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')

<script type="text/javascript">
    $(function () {

        if($('#salesChart').length > 0) {

            let salesData = @json($monthly_sales_data);
            let salesChart = $('#salesChart').get(0).getContext('2d');
            let months = [];
            let sums = [];

            $.each(salesData, function(index, value) {
                months[index] = value.months;
                sums[index] = value.sums;
            });

            new Chart(salesChart, {
                type: 'line',
                data: {
                    labels  : months,
                    datasets: [{
                        data: sums,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                    },
                }
            });
        }

        if($('#projectListingChart').length > 0) {

            let projectsListingData = @json($monthly_listing_projects);
            let projectsListingChart = $('#projectListingChart').get(0).getContext('2d');
            let months = [];
            let counts = [];

            $.each(projectsListingData, function(index, value) {
                months[index] = value.months;
                counts[index] = value.monthly_projects_count;
            });

            new Chart(projectsListingChart, {
                type: 'line',
                data: {
                    labels  : months,
                    datasets: [{
                        data: counts,
                        fill: false,
                        borderColor: ' #f68d53',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                    },
                }
            });
        }

    });
</script>

@endpush