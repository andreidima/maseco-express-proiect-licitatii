@extends('layouts.app')

@section('body-class', 'ltm-panou-body')
@section('main-class', 'ltm-panou-main')

@section('content')
<div class="container py-4 ltm-dashboard">
    @php
        $notProvided = __('pages/home.not_provided');

        $statusDistribution = collect($stats['auctionStatusDistribution'] ?? []);
        $typeDistribution = collect($stats['auctionTypeDistribution'] ?? []);
        $auctionsPerClient = collect($stats['auctionsPerClient'] ?? []);
        $auctionsPerRoute = collect($stats['auctionsPerRoute'] ?? []);
        $topAuctions = collect($stats['topAuctionsByEstimatedValue'] ?? []);
        $valueSummary = $stats['auctionEstimatedValueSummary'] ?? null;

        $statusLabels = $statusDistribution->map(fn ($row) => $row->status ?? $notProvided)->values()->all();
        $statusTotals = $statusDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $typeLabels = $typeDistribution->map(fn ($row) => $row->type ?? $notProvided)->values()->all();
        $typeTotals = $typeDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $clientLabels = $auctionsPerClient->map(fn ($row) => $row->client?->name ?? $notProvided)->values()->all();
        $clientTotals = $auctionsPerClient->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $routeLabels = $auctionsPerRoute->map(function ($row) use ($notProvided) {
            $route = $row->route;
            if (!$route) return $notProvided;
            return $route->code ?: trim(($route->origin_city ?? '') . ' -> ' . ($route->destination_city ?? '')) ?: $notProvided;
        })->values()->all();
        $routeTotals = $auctionsPerRoute->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $topAuctionLabels = $topAuctions->map(fn ($a) => $a->auction_number ?? (string) $a->id)->values()->all();
        $topAuctionValues = $topAuctions->map(fn ($a) => (float) ($a->estimated_value_eur ?? 0))->values()->all();

        $statusChart = [
            'type' => 'doughnut',
            'data' => [
                'labels' => $statusLabels,
                'datasets' => [[ 'data' => $statusTotals ]],
            ],
        ];

        $typeChart = [
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

        $perClientChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $clientLabels,
                'datasets' => [[ 'data' => $clientTotals, 'borderRadius' => 10 ]],
            ],
            'options' => [
                'indexAxis' => 'y',
                'plugins' => ['legend' => ['display' => false]],
                'scales' => ['x' => ['beginAtZero' => true]],
            ],
        ];

        $perRouteChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $routeLabels,
                'datasets' => [[ 'data' => $routeTotals, 'borderRadius' => 10 ]],
            ],
            'options' => [
                'plugins' => ['legend' => ['display' => false]],
                'scales' => ['y' => ['beginAtZero' => true]],
            ],
        ];

        $topAuctionsChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $topAuctionLabels,
                'datasets' => [[
                    'label' => __('pages/reports.auctions_chart_top_value_legend'),
                    'data' => $topAuctionValues,
                    'borderRadius' => 10,
                ]],
            ],
            'options' => [
                'plugins' => ['legend' => ['position' => 'bottom']],
                'scales' => ['y' => ['beginAtZero' => true]],
            ],
        ];
    @endphp

    <div class="card shadow-sm border-0 home-hero mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
                <div>
                    <span class="badge bg-light text-dark home-hero-badge mb-2">{{ __('pages/reports.auctions_badge') }}</span>
                    <h2 class="fw-bold mb-1">{{ __('pages/reports.auctions_title') }}</h2>
                    <p class="mb-0 opacity-75">{{ __('pages/reports.auctions_subtitle') }}</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-white text-dark shadow-sm">{{ __('pages/reports.kpi_total') }}: {{ number_format($stats['totalAuctions'] ?? 0) }}</span>
                    @if ($valueSummary)
                        <span class="badge bg-white text-dark shadow-sm">{{ __('pages/reports.kpi_value_sum') }}: {{ number_format((float) ($valueSummary->sum_value ?? 0), 0, ',', '.') }}</span>
                        <span class="badge bg-white text-dark shadow-sm">{{ __('pages/reports.kpi_value_avg') }}: {{ number_format((float) ($valueSummary->avg_value ?? 0), 0, ',', '.') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 home-charts-row">
        <div class="col-lg-4">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.auctions_chart_status') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="260" data-chart-config='@json($statusChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.auctions_chart_type') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="260" data-chart-config='@json($typeChart)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3 home-charts-row">
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.auctions_chart_per_client') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="320" data-chart-config='@json($perClientChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.auctions_chart_per_route') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="320" data-chart-config='@json($perRouteChart)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3 home-charts-row">
        <div class="col-12">
            <div class="card home-chart-card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.auctions_chart_top_value') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="320" data-chart-config='@json($topAuctionsChart)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="card home-list-card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.auctions_table_top_value') }}</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('pages/reports.column_auction') }}</th>
                                <th>{{ __('pages/reports.column_client') }}</th>
                                <th class="text-end">{{ __('pages/reports.column_value') }}</th>
                                <th>{{ __('pages/reports.column_status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topAuctions as $auction)
                                <tr>
                                    <td>{{ $auction->auction_number ?? $notProvided }}</td>
                                    <td>{{ $auction->client?->name ?? $notProvided }}</td>
                                    <td class="text-end">{{ number_format((float) ($auction->estimated_value_eur ?? 0), 0, ',', '.') }} {{ $auction->currency?->code ?? '' }}</td>
                                    <td>{{ $auction->status ?? $notProvided }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted text-center">{{ __('pages/home.section_empty') }}</td>
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

