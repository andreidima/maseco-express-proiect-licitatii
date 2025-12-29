@extends('layouts.app')

@section('body-class', 'ltm-panou-body')
@section('main-class', 'ltm-panou-main')

@section('content')
<div class="container py-4 ltm-dashboard">
    @php
        $notProvided = __('pages/home.not_provided');

        $supportStatusDistribution = collect($stats['supportStatusDistribution'] ?? []);
        $supportSeverityDistribution = collect($stats['supportSeverityDistribution'] ?? []);
        $supportCategoryDistribution = collect($stats['supportByCategory'] ?? ($stats['supportCategoryDistribution'] ?? []));
        $supportTypeDistribution = collect($stats['supportTypeDistribution'] ?? []);

        $notificationAudienceDistribution = collect($stats['notificationAudienceDistribution'] ?? []);
        $notificationTypeDistribution = collect($stats['notificationTypeDistribution'] ?? []);

        $supportStatusLabels = $supportStatusDistribution->map(fn ($row) => ucfirst($row->status ?? $notProvided))->values()->all();
        $supportStatusTotals = $supportStatusDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $supportSeverityLabels = $supportSeverityDistribution->map(fn ($row) => $row->problem_severity ?? $notProvided)->values()->all();
        $supportSeverityTotals = $supportSeverityDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $supportCategoryLabels = $supportCategoryDistribution->map(fn ($row) => $row->problem_category ?? $notProvided)->values()->all();
        $supportCategoryTotals = $supportCategoryDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $supportTypeLabels = $supportTypeDistribution->map(fn ($row) => ucfirst($row->type ?? $notProvided))->values()->all();
        $supportTypeTotals = $supportTypeDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $audienceLabels = $notificationAudienceDistribution->map(fn ($row) => ucfirst($row->audience ?? $notProvided))->values()->all();
        $audienceTotals = $notificationAudienceDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $typeLabels = $notificationTypeDistribution->map(fn ($row) => $row->type ?? $notProvided)->values()->all();
        $typeTotals = $notificationTypeDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $notificationTotal = $notificationAudienceDistribution->pluck('total')->map(fn ($v) => (int) $v)->sum();

        $supportStatusChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $supportStatusLabels,
                'datasets' => [[ 'data' => $supportStatusTotals, 'borderRadius' => 10 ]],
            ],
            'options' => [
                'plugins' => ['legend' => ['display' => false]],
                'scales' => ['y' => ['beginAtZero' => true]],
            ],
        ];

        $supportSeverityChart = [
            'type' => 'doughnut',
            'data' => [
                'labels' => $supportSeverityLabels,
                'datasets' => [[ 'data' => $supportSeverityTotals ]],
            ],
        ];

        $supportCategoryChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $supportCategoryLabels,
                'datasets' => [[ 'data' => $supportCategoryTotals, 'borderRadius' => 10 ]],
            ],
            'options' => [
                'indexAxis' => 'y',
                'plugins' => ['legend' => ['display' => false]],
                'scales' => ['x' => ['beginAtZero' => true]],
            ],
        ];

        $supportTypeChart = [
            'type' => 'pie',
            'data' => [
                'labels' => $supportTypeLabels,
                'datasets' => [[ 'data' => $supportTypeTotals, 'borderWidth' => 1 ]],
            ],
        ];

        $notificationAudienceChart = [
            'type' => 'doughnut',
            'data' => [
                'labels' => $audienceLabels,
                'datasets' => [[ 'data' => $audienceTotals ]],
            ],
        ];

        $notificationTypesChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $typeLabels,
                'datasets' => [[ 'data' => $typeTotals, 'borderRadius' => 10 ]],
            ],
            'options' => [
                'plugins' => ['legend' => ['display' => false]],
                'scales' => ['y' => ['beginAtZero' => true]],
            ],
        ];
    @endphp

    <div class="card shadow-sm border-0 home-hero mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
                <div>
                    <span class="badge bg-light text-dark home-hero-badge mb-2">{{ __('pages/reports.support_badge') }}</span>
                    <h2 class="fw-bold mb-1">{{ __('pages/reports.support_title') }}</h2>
                    <p class="mb-0 opacity-75">{{ __('pages/reports.support_subtitle') }}</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-white text-dark shadow-sm">{{ __('pages/reports.support_kpi_threads') }}: {{ number_format($stats['supportThreadsCount'] ?? 0) }}</span>
                    <span class="badge bg-white text-dark shadow-sm">{{ __('pages/reports.support_kpi_notifications') }}: {{ number_format($notificationTotal) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 home-charts-row">
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.support_status') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="280" data-chart-config='@json($supportStatusChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.support_severity') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="280" data-chart-config='@json($supportSeverityChart)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3 home-charts-row">
        <div class="col-lg-7">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.support_category') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="320" data-chart-config='@json($supportCategoryChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.support_chart_type') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="320" data-chart-config='@json($supportTypeChart)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3 home-charts-row">
        <div class="col-lg-5">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.support_notifications') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="280" data-chart-config='@json($notificationAudienceChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.support_notification_types') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="280" data-chart-config='@json($notificationTypesChart)'></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

