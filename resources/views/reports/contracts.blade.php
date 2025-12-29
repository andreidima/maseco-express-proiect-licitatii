@extends('layouts.app')

@section('body-class', 'ltm-panou-body')
@section('main-class', 'ltm-panou-main')

@section('content')
<div class="container py-4 ltm-dashboard">
    @php
        $notProvided = __('pages/home.not_provided');

        $statusDistribution = collect($stats['contractStatusDistribution'] ?? []);
        $typeDistribution = collect($stats['contractTypeDistribution'] ?? []);
        $valueByStatus = collect($stats['contractValueByStatus'] ?? []);
        $topClients = collect($stats['topClientsByContractValue'] ?? []);
        $topCarriers = collect($stats['topCarriersByContractValue'] ?? []);
        $summary = $stats['contractValueSummary'] ?? null;

        $statusLabels = $statusDistribution->map(fn ($row) => $row->status ?? $notProvided)->values()->all();
        $statusTotals = $statusDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $typeLabels = $typeDistribution->map(fn ($row) => $row->contract_type ?? $notProvided)->values()->all();
        $typeTotals = $typeDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $valueStatusLabels = $valueByStatus->map(fn ($row) => $row->status ?? $notProvided)->values()->all();
        $valueCounts = $valueByStatus->pluck('total')->map(fn ($v) => (int) $v)->values()->all();
        $valueSums = $valueByStatus->pluck('sum_value')->map(fn ($v) => (float) ($v ?? 0))->values()->all();

        $topClientLabels = $topClients->map(fn ($row) => $row->client?->name ?? $notProvided)->values()->all();
        $topClientValues = $topClients->map(fn ($row) => (float) ($row->total_value ?? 0))->values()->all();

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

        $valueChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $valueStatusLabels,
                'datasets' => [
                    [
                        'label' => __('pages/reports.contracts_chart_count'),
                        'data' => $valueCounts,
                        'borderRadius' => 10,
                    ],
                    [
                        'label' => __('pages/reports.contracts_chart_value'),
                        'data' => $valueSums,
                        'borderRadius' => 10,
                    ],
                ],
            ],
            'options' => [
                'plugins' => ['legend' => ['position' => 'bottom']],
                'scales' => ['y' => ['beginAtZero' => true]],
            ],
        ];

        $topClientsChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $topClientLabels,
                'datasets' => [[
                    'label' => __('pages/reports.contracts_chart_top_clients_legend'),
                    'data' => $topClientValues,
                    'borderRadius' => 10,
                ]],
            ],
            'options' => [
                'indexAxis' => 'y',
                'plugins' => ['legend' => ['position' => 'bottom']],
                'scales' => ['x' => ['beginAtZero' => true]],
            ],
        ];
    @endphp

    <div class="card shadow-sm border-0 home-hero mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
                <div>
                    <span class="badge bg-light text-dark home-hero-badge mb-2">{{ __('pages/reports.contracts_badge') }}</span>
                    <h2 class="fw-bold mb-1">{{ __('pages/reports.contracts_title') }}</h2>
                    <p class="mb-0 opacity-75">{{ __('pages/reports.contracts_subtitle') }}</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-white text-dark shadow-sm">{{ __('pages/reports.kpi_total') }}: {{ number_format($stats['totalContracts'] ?? 0) }}</span>
                    @if ($summary)
                        <span class="badge bg-white text-dark shadow-sm">{{ __('pages/reports.kpi_value_sum') }}: {{ number_format((float) ($summary->sum_value ?? 0), 0, ',', '.') }}</span>
                        <span class="badge bg-white text-dark shadow-sm">{{ __('pages/reports.kpi_value_avg') }}: {{ number_format((float) ($summary->avg_value ?? 0), 0, ',', '.') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 home-charts-row">
        <div class="col-lg-4">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.contracts_chart_status') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="260" data-chart-config='@json($statusChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.contracts_chart_type') }}</h6>
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
                    <h6 class="mb-0">{{ __('pages/reports.contracts_chart_value_by_status') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="300" data-chart-config='@json($valueChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.contracts_chart_top_clients') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="300" data-chart-config='@json($topClientsChart)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-lg-6">
            <div class="card home-list-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.contracts_table_top_clients') }}</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('pages/reports.column_client') }}</th>
                                <th class="text-end">{{ __('pages/reports.column_value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topClients as $row)
                                <tr>
                                    <td>{{ $row->client?->name ?? $notProvided }}</td>
                                    <td class="text-end">{{ number_format((float) ($row->total_value ?? 0), 0, ',', '.') }} {{ $row->currency?->code ?? '' }}</td>
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
                    <h6 class="mb-0">{{ __('pages/reports.contracts_table_top_carriers') }}</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('pages/reports.column_carrier') }}</th>
                                <th class="text-end">{{ __('pages/reports.column_value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topCarriers as $row)
                                <tr>
                                    <td>{{ $row->carrier?->name ?? $notProvided }}</td>
                                    <td class="text-end">{{ number_format((float) ($row->total_value ?? 0), 0, ',', '.') }} {{ $row->currency?->code ?? '' }}</td>
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
    </div>
</div>
@endsection

