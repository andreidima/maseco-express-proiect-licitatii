@extends('layouts.app')

@section('body-class', 'ltm-panou-body')
@section('main-class', 'ltm-panou-main')

@section('content')
<div class="container py-4 ltm-dashboard">
    @php
        $ltmKpis = $stats['ltmKpis'] ?? [];

        $notificationAudienceDistribution = collect($stats['notificationAudienceDistribution'] ?? []);
        $notificationTypeDistribution = collect($stats['notificationTypeDistribution'] ?? []);
        $supportStatusDistribution = collect($stats['supportStatusDistribution'] ?? []);
        $supportSeverityDistribution = collect($stats['supportSeverityDistribution'] ?? []);
        $supportCategoryDistribution = collect($stats['supportCategoryDistribution'] ?? []);

        $activationDistribution = collect($stats['userActivationDistribution'] ?? []);
        $roleDistribution = collect($stats['userRoleDistribution'] ?? []);

        $auctionStatusDistribution = collect($stats['auctionStatusDistribution'] ?? []);
        $auctionTypeDistribution = collect($stats['auctionTypeDistribution'] ?? []);
        $bidStatusDistribution = collect($stats['bidStatusDistribution'] ?? []);
        $contractStatusDistribution = collect($stats['contractStatusDistribution'] ?? []);

        $ltmTopClients = collect($stats['ltmTopClients'] ?? []);
        $ltmTopCarriers = collect($stats['ltmTopCarriers'] ?? []);
        $ltmTopAuctions = collect($stats['ltmTopAuctions'] ?? []);

        $bidPriceVsFuelScatter = collect($stats['bidPriceVsFuelScatter'] ?? [])
            ->filter(fn ($row) => is_numeric($row->price_per_trip_eur ?? null) && is_numeric($row->surcharge_fuel_percent ?? null))
            ->map(fn ($row) => ['x' => (float) $row->price_per_trip_eur, 'y' => (float) $row->surcharge_fuel_percent])
            ->values()
            ->all();

        $notProvided = __('pages/home.not_provided');

        $activationLabels = $activationDistribution->map(function ($row) {
            if (($row->label ?? '') === 'active') return __('pages/home.activation_active');
            if (($row->label ?? '') === 'inactive') return __('pages/home.activation_inactive');
            return $row->label ?? __('pages/home.not_provided');
        })->values()->all();
        $activationTotals = $activationDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $roleLabels = $roleDistribution->map(fn ($row) => $row->role ?? $notProvided)->values()->all();
        $roleTotals = $roleDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $audienceLabels = $notificationAudienceDistribution->map(fn ($row) => ucfirst($row->audience ?? $notProvided))->values()->all();
        $audienceTotals = $notificationAudienceDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $typeLabels = $notificationTypeDistribution->map(fn ($row) => $row->type ?? $notProvided)->values()->all();
        $typeTotals = $notificationTypeDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $supportStatusLabels = $supportStatusDistribution->map(fn ($row) => ucfirst($row->status ?? $notProvided))->values()->all();
        $supportStatusTotals = $supportStatusDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $supportSeverityLabels = $supportSeverityDistribution->map(fn ($row) => $row->problem_severity ?? $notProvided)->values()->all();
        $supportSeverityTotals = $supportSeverityDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $supportCategoryLabels = $supportCategoryDistribution->map(fn ($row) => $row->problem_category ?? $notProvided)->values()->all();
        $supportCategoryTotals = $supportCategoryDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $auctionStatusLabels = $auctionStatusDistribution->map(fn ($row) => $row->status ?? $notProvided)->values()->all();
        $auctionStatusTotals = $auctionStatusDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $auctionTypeLabels = $auctionTypeDistribution->map(fn ($row) => $row->type ?? $notProvided)->values()->all();
        $auctionTypeTotals = $auctionTypeDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $bidStatusLabels = $bidStatusDistribution->map(fn ($row) => $row->status ?? $notProvided)->values()->all();
        $bidStatusTotals = $bidStatusDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $contractStatusLabels = $contractStatusDistribution->map(fn ($row) => $row->status ?? $notProvided)->values()->all();
        $contractStatusTotals = $contractStatusDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $notificationTotal = $notificationAudienceDistribution->pluck('total')->map(fn ($v) => (int) $v)->sum();

        $activationChart = [
            'type' => 'doughnut',
            'data' => [
                'labels' => $activationLabels,
                'datasets' => [[ 'data' => $activationTotals ]],
            ],
            'options' => [
                'plugins' => [
                    'legend' => ['position' => 'bottom'],
                ],
            ],
        ];

        $rolesChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $roleLabels,
                'datasets' => [[ 'data' => $roleTotals, 'borderRadius' => 10 ]],
            ],
            'options' => [
                'plugins' => ['legend' => ['display' => false]],
                'scales' => ['y' => ['beginAtZero' => true]],
            ],
        ];

        $auctionStatusChart = [
            'type' => 'polarArea',
            'data' => [
                'labels' => $auctionStatusLabels,
                'datasets' => [[ 'data' => $auctionStatusTotals, 'borderWidth' => 1 ]],
            ],
        ];

        $auctionTypeChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $auctionTypeLabels,
                'datasets' => [[ 'data' => $auctionTypeTotals, 'borderRadius' => 10 ]],
            ],
            'options' => [
                'plugins' => ['legend' => ['display' => false]],
                'scales' => ['y' => ['beginAtZero' => true]],
            ],
        ];

        $bidStatusChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $bidStatusLabels,
                'datasets' => [[ 'data' => $bidStatusTotals, 'borderRadius' => 10 ]],
            ],
            'options' => [
                'indexAxis' => 'y',
                'plugins' => ['legend' => ['display' => false]],
                'scales' => ['x' => ['beginAtZero' => true]],
            ],
        ];

        $contractStatusChart = [
            'type' => 'doughnut',
            'data' => [
                'labels' => $contractStatusLabels,
                'datasets' => [[ 'data' => $contractStatusTotals ]],
            ],
        ];

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
            'type' => 'pie',
            'data' => [
                'labels' => $supportSeverityLabels,
                'datasets' => [[ 'data' => $supportSeverityTotals, 'borderWidth' => 1 ]],
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

        $bidScatterChart = [
            'type' => 'scatter',
            'data' => [
                'datasets' => [[
                    'label' => __('pages/home.chart_bid_scatter_legend'),
                    'data' => $bidPriceVsFuelScatter,
                    'pointRadius' => 4,
                    'borderWidth' => 0,
                ]],
            ],
            'options' => [
                'plugins' => ['legend' => ['position' => 'bottom']],
                'scales' => [
                    'x' => [
                        'title' => ['display' => true, 'text' => __('pages/home.chart_bid_scatter_x')],
                    ],
                    'y' => [
                        'title' => ['display' => true, 'text' => __('pages/home.chart_bid_scatter_y')],
                        'beginAtZero' => true,
                    ],
                ],
            ],
        ];
    @endphp

    <div class="card shadow-sm border-0 home-hero mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
                <div>
                    <span class="badge bg-light text-dark home-hero-badge mb-2">{{ __('pages/home.hero_title') }}</span>
                    <h2 class="fw-bold mb-2">{{ __('pages/home.dashboard') }}</h2>
                    <p class="mb-0 opacity-75">{{ __('pages/home.hero_subtitle') }}</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-light fw-semibold" href="{{ route('reports.auctions') }}">
                        <i class="fa-solid fa-chart-column me-1"></i> {{ __('pages/home.cta_reports') }}
                    </a>
                    <a class="btn btn-outline-light fw-semibold" href="{{ route('reports.support') }}">
                        <i class="fa-solid fa-headset me-1"></i> {{ __('pages/home.cta_support') }}
                    </a>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <span class="badge bg-white text-dark shadow-sm">{{ __('pages/home.summary_total_users') }}: {{ number_format($stats['totalUsers'] ?? 0) }}</span>
                <span class="badge bg-white text-dark shadow-sm">{{ __('pages/home.stats_total_auctions') }}: {{ number_format($ltmKpis['totalAuctions'] ?? 0) }}</span>
                <span class="badge bg-white text-dark shadow-sm">{{ __('pages/home.stats_total_lots') }}: {{ number_format($ltmKpis['totalLots'] ?? 0) }}</span>
                <span class="badge bg-white text-dark shadow-sm">{{ __('pages/home.kpi_total_bids') }}: {{ number_format($ltmKpis['totalBids'] ?? 0) }}</span>
                <span class="badge bg-white text-dark shadow-sm">{{ __('pages/home.kpi_total_contracts') }}: {{ number_format($ltmKpis['totalContracts'] ?? 0) }}</span>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4 home-kpi-grid">
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0 home-kpi-card home-kpi-card--teal h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="home-kpi-label mb-1 text-uppercase">{{ __('pages/home.summary_total_users') }}</p>
                            <div class="home-kpi-value">{{ number_format($stats['totalUsers'] ?? 0) }}</div>
                        </div>
                        <i class="fa-solid fa-users fa-lg opacity-75"></i>
                    </div>
                    <div class="home-kpi-note mt-2">
                        {{ __('pages/home.summary_active_users') }}: {{ number_format($stats['activeUsers'] ?? 0) }}<br>
                        {{ __('pages/home.summary_inactive_users') }}: {{ number_format($stats['inactiveUsers'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0 home-kpi-card home-kpi-card--violet h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="home-kpi-label mb-1 text-uppercase">{{ __('pages/home.stats_total_auctions') }}</p>
                            <div class="home-kpi-value">{{ number_format($ltmKpis['totalAuctions'] ?? 0) }}</div>
                        </div>
                        <i class="fa-solid fa-gavel fa-lg opacity-75"></i>
                    </div>
                    <div class="home-kpi-note mt-2">
                        {{ __('pages/home.stats_total_lots') }}: {{ number_format($ltmKpis['totalLots'] ?? 0) }}<br>
                        {{ __('pages/home.kpi_total_routes') }}: {{ number_format($ltmKpis['totalRoutes'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0 home-kpi-card home-kpi-card--amber h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="home-kpi-label mb-1 text-uppercase">{{ __('pages/home.kpi_total_bids') }}</p>
                            <div class="home-kpi-value">{{ number_format($ltmKpis['totalBids'] ?? 0) }}</div>
                        </div>
                        <i class="fa-solid fa-tags fa-lg opacity-75"></i>
                    </div>
                    <div class="home-kpi-note mt-2">
                        {{ __('pages/home.kpi_total_clients') }}: {{ number_format($ltmKpis['totalClients'] ?? 0) }}<br>
                        {{ __('pages/home.kpi_total_carriers') }}: {{ number_format($ltmKpis['totalCarriers'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0 home-kpi-card home-kpi-card--green h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="home-kpi-label mb-1 text-uppercase">{{ __('pages/home.kpi_total_contracts') }}</p>
                            <div class="home-kpi-value">{{ number_format($ltmKpis['totalContracts'] ?? 0) }}</div>
                        </div>
                        <i class="fa-solid fa-file-contract fa-lg opacity-75"></i>
                    </div>
                    <div class="home-kpi-note mt-2">
                        {{ __('pages/home.kpi_total_notifications') }}: {{ number_format($notificationTotal) }}<br>
                        {{ __('pages/home.summary_support_threads') }}: {{ number_format($stats['supportThreadsCount'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 home-charts-row">
        <div class="col-lg-4">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/home.chart_activation_title') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="250" data-chart-config='@json($activationChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/home.chart_roles_title') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="250" data-chart-config='@json($rolesChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/home.chart_notifications_audience_title') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="250" data-chart-config='@json($notificationAudienceChart)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3 home-charts-row">
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/home.chart_auction_status_title') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="260" data-chart-config='@json($auctionStatusChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/home.chart_auction_types_title') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="260" data-chart-config='@json($auctionTypeChart)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3 home-charts-row">
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/home.chart_bid_status_title') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="290" data-chart-config='@json($bidStatusChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/home.chart_contract_status_title') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="290" data-chart-config='@json($contractStatusChart)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3 home-charts-row">
        <div class="col-lg-4">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/home.support_status_title') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="260" data-chart-config='@json($supportStatusChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/home.support_severity_title') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="260" data-chart-config='@json($supportSeverityChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/home.chart_support_categories_title') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="260" data-chart-config='@json($supportCategoryChart)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3 home-charts-row">
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/home.chart_notifications_types_title') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="280" data-chart-config='@json($notificationTypesChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/home.chart_bid_scatter_title') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="280" data-chart-config='@json($bidScatterChart)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-4">
        <div class="col-lg-6">
            <div class="card home-list-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/home.table_top_clients') }}</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('pages/home.column_metric') }}</th>
                                <th class="text-end">{{ __('pages/home.column_value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ltmTopClients as $item)
                                <tr>
                                    <td>{{ $item->client?->name ?? $notProvided }}</td>
                                    <td class="text-end">{{ number_format((float) $item->total_value, 0, ',', '.') }} {{ $item->currency?->code ?? '' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-muted text-center">{{ __('pages/home.section_empty') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card home-list-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/home.table_top_carriers') }}</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('pages/home.column_metric') }}</th>
                                <th class="text-end">{{ __('pages/home.column_value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ltmTopCarriers as $item)
                                <tr>
                                    <td>{{ $item->carrier?->name ?? $notProvided }}</td>
                                    <td class="text-end">{{ number_format((float) $item->total_value, 0, ',', '.') }} {{ $item->currency?->code ?? '' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-muted text-center">{{ __('pages/home.section_empty') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card home-list-card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/home.table_top_auctions') }}</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('pages/home.table_column_auction') }}</th>
                                <th>{{ __('pages/home.table_column_client') }}</th>
                                <th class="text-end">{{ __('pages/home.column_value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ltmTopAuctions as $auction)
                                <tr>
                                    <td>{{ $auction->auction_number ?? $notProvided }}</td>
                                    <td>{{ $auction->client?->name ?? $notProvided }}</td>
                                    <td class="text-end">{{ number_format((float) ($auction->estimated_value_eur ?? 0), 0, ',', '.') }} {{ $auction->currency?->code ?? '' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted text-center">{{ __('pages/home.section_empty') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

