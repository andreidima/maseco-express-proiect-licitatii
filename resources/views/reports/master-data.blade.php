@extends('layouts.app')

@section('body-class', 'ltm-panou-body')
@section('main-class', 'ltm-panou-main')

@section('content')
<div class="container py-4 ltm-dashboard">
    @php
        $notProvided = __('pages/home.not_provided');

        $clientsByCountry = collect($stats['clientsByCountry'] ?? []);
        $carriersByCountry = collect($stats['carriersByCountry'] ?? []);
        $routesByOriginCountry = collect($stats['routesByOriginCountry'] ?? []);
        $routesByDestinationCountry = collect($stats['routesByDestinationCountry'] ?? []);

        $clientCountryLabels = $clientsByCountry->map(fn ($row) => $row->country ?: $notProvided)->values()->all();
        $clientCountryTotals = $clientsByCountry->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $carrierCountryLabels = $carriersByCountry->map(fn ($row) => $row->country ?: $notProvided)->values()->all();
        $carrierCountryTotals = $carriersByCountry->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $originLabels = $routesByOriginCountry->map(fn ($row) => $row->origin_country ?: $notProvided)->values()->all();
        $originTotals = $routesByOriginCountry->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $destLabels = $routesByDestinationCountry->map(fn ($row) => $row->destination_country ?: $notProvided)->values()->all();
        $destTotals = $routesByDestinationCountry->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $clientsByCountryChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $clientCountryLabels,
                'datasets' => [[ 'data' => $clientCountryTotals, 'borderRadius' => 10 ]],
            ],
            'options' => [
                'indexAxis' => 'y',
                'plugins' => ['legend' => ['display' => false]],
                'scales' => ['x' => ['beginAtZero' => true]],
            ],
        ];

        $carriersByCountryChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $carrierCountryLabels,
                'datasets' => [[ 'data' => $carrierCountryTotals, 'borderRadius' => 10 ]],
            ],
            'options' => [
                'plugins' => ['legend' => ['display' => false]],
                'scales' => ['y' => ['beginAtZero' => true]],
            ],
        ];

        $routesOriginChart = [
            'type' => 'doughnut',
            'data' => [
                'labels' => $originLabels,
                'datasets' => [[ 'data' => $originTotals ]],
            ],
        ];

        $routesDestinationChart = [
            'type' => 'pie',
            'data' => [
                'labels' => $destLabels,
                'datasets' => [[ 'data' => $destTotals, 'borderWidth' => 1 ]],
            ],
        ];
    @endphp

    <div class="card shadow-sm border-0 home-hero mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
                <div>
                    <span class="badge bg-light text-dark home-hero-badge mb-2">{{ __('pages/reports.master_data_badge') }}</span>
                    <h2 class="fw-bold mb-1">{{ __('pages/reports.master_data_title') }}</h2>
                    <p class="mb-0 opacity-75">{{ __('pages/reports.master_data_subtitle') }}</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-white text-dark shadow-sm">{{ __('pages/reports.master_data_kpi_clients') }}: {{ number_format($stats['clientsCount'] ?? 0) }}</span>
                    <span class="badge bg-white text-dark shadow-sm">{{ __('pages/reports.master_data_kpi_carriers') }}: {{ number_format($stats['carriersCount'] ?? 0) }}</span>
                    <span class="badge bg-white text-dark shadow-sm">{{ __('pages/reports.master_data_kpi_routes') }}: {{ number_format($stats['routesCount'] ?? 0) }}</span>
                    <span class="badge bg-white text-dark shadow-sm">{{ __('pages/reports.master_data_kpi_currencies') }}: {{ number_format($stats['currenciesCount'] ?? 0) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 home-charts-row">
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.master_data_chart_clients_by_country') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="320" data-chart-config='@json($clientsByCountryChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.master_data_chart_carriers_by_country') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="320" data-chart-config='@json($carriersByCountryChart)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3 home-charts-row">
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.master_data_chart_routes_origin') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="280" data-chart-config='@json($routesOriginChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.master_data_chart_routes_destination') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="280" data-chart-config='@json($routesDestinationChart)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100 ltm-kpi-card ltm-kpi-card--teal">
                <div class="card-body">
                    <p class="ltm-kpi-label mb-1">{{ __('pages/reports.master_data_kpi_auctions') }}</p>
                    <h3 class="fw-bold ltm-kpi-value mb-0">{{ number_format($stats['auctionsCount'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100 ltm-kpi-card ltm-kpi-card--cobalt">
                <div class="card-body">
                    <p class="ltm-kpi-label mb-1">{{ __('pages/reports.master_data_kpi_bids') }}</p>
                    <h3 class="fw-bold ltm-kpi-value mb-0">{{ number_format($stats['bidsCount'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100 ltm-kpi-card ltm-kpi-card--emerald">
                <div class="card-body">
                    <p class="ltm-kpi-label mb-1">{{ __('pages/reports.master_data_kpi_contracts') }}</p>
                    <h3 class="fw-bold ltm-kpi-value mb-0">{{ number_format($stats['contractsCount'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

