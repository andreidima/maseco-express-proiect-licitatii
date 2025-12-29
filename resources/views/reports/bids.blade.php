@extends('layouts.app')

@section('body-class', 'ltm-panou-body')
@section('main-class', 'ltm-panou-main')

@section('content')
<div class="container py-4 ltm-dashboard">
    @php
        $notProvided = __('pages/home.not_provided');

        $statusDistribution = collect($stats['bidStatusDistribution'] ?? []);
        $bidsPerCarrier = collect($stats['bidsPerCarrier'] ?? []);
        $bidsPerAuction = collect($stats['bidsPerAuction'] ?? []);
        $avgByStatus = collect($stats['bidAveragesByStatus'] ?? []);

        $statusLabels = $statusDistribution->map(fn ($row) => $row->status ?? $notProvided)->values()->all();
        $statusTotals = $statusDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $carrierLabels = $bidsPerCarrier->map(fn ($row) => $row->carrier?->name ?? $notProvided)->values()->all();
        $carrierTotals = $bidsPerCarrier->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $auctionLabels = $bidsPerAuction->map(fn ($row) => $row->auction?->auction_number ?? (string) ($row->auction_id ?? $notProvided))->values()->all();
        $auctionTotals = $bidsPerAuction->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $avgLabels = $avgByStatus->map(fn ($row) => $row->status ?? $notProvided)->values()->all();
        $avgTrip = $avgByStatus->map(fn ($row) => (float) ($row->avg_price_per_trip_eur ?? 0))->values()->all();
        $avgTon = $avgByStatus->map(fn ($row) => (float) ($row->avg_price_per_ton_eur ?? 0))->values()->all();

        $statusChart = [
            'type' => 'doughnut',
            'data' => [
                'labels' => $statusLabels,
                'datasets' => [[ 'data' => $statusTotals ]],
            ],
        ];

        $perCarrierChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $carrierLabels,
                'datasets' => [[ 'data' => $carrierTotals, 'borderRadius' => 10 ]],
            ],
            'options' => [
                'indexAxis' => 'y',
                'plugins' => ['legend' => ['display' => false]],
                'scales' => ['x' => ['beginAtZero' => true]],
            ],
        ];

        $perAuctionChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $auctionLabels,
                'datasets' => [[ 'data' => $auctionTotals, 'borderRadius' => 10 ]],
            ],
            'options' => [
                'plugins' => ['legend' => ['display' => false]],
                'scales' => ['y' => ['beginAtZero' => true]],
            ],
        ];

        $avgPriceChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $avgLabels,
                'datasets' => [
                    [
                        'label' => __('pages/reports.bids_chart_avg_trip'),
                        'data' => $avgTrip,
                        'borderRadius' => 10,
                    ],
                    [
                        'label' => __('pages/reports.bids_chart_avg_ton'),
                        'data' => $avgTon,
                        'borderRadius' => 10,
                    ],
                ],
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
                    <span class="badge bg-light text-dark home-hero-badge mb-2">{{ __('pages/reports.bids_badge') }}</span>
                    <h2 class="fw-bold mb-1">{{ __('pages/reports.bids_title') }}</h2>
                    <p class="mb-0 opacity-75">{{ __('pages/reports.bids_subtitle') }}</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-white text-dark shadow-sm">{{ __('pages/reports.kpi_total') }}: {{ number_format($stats['totalBids'] ?? 0) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 home-charts-row">
        <div class="col-lg-4">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.bids_chart_status') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="260" data-chart-config='@json($statusChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.bids_chart_per_carrier') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="260" data-chart-config='@json($perCarrierChart)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3 home-charts-row">
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.bids_chart_per_auction') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="300" data-chart-config='@json($perAuctionChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.bids_chart_avg_prices') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="300" data-chart-config='@json($avgPriceChart)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="card home-list-card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.bids_table_avg_by_status') }}</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('pages/reports.column_status') }}</th>
                                <th class="text-end">{{ __('pages/reports.bids_column_avg_trip') }}</th>
                                <th class="text-end">{{ __('pages/reports.bids_column_avg_ton') }}</th>
                                <th class="text-end">{{ __('pages/reports.bids_column_avg_fuel') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($avgByStatus as $row)
                                <tr>
                                    <td>{{ $row->status ?? $notProvided }}</td>
                                    <td class="text-end">{{ number_format((float) ($row->avg_price_per_trip_eur ?? 0), 2, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format((float) ($row->avg_price_per_ton_eur ?? 0), 2, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format((float) ($row->avg_surcharge_fuel_percent ?? 0), 2, ',', '.') }}%</td>
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

