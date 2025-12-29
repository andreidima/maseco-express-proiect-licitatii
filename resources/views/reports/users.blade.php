@extends('layouts.app')

@section('body-class', 'ltm-panou-body')
@section('main-class', 'ltm-panou-main')

@section('content')
<div class="container py-4 ltm-dashboard">
    @php
        $notProvided = __('pages/home.not_provided');

        $roleDistribution = collect($stats['userRoleDistribution'] ?? []);
        $activationDistribution = collect($stats['userActivationDistribution'] ?? []);
        $usersPerCarrier = collect($stats['usersPerCarrier'] ?? []);

        $activationLabels = $activationDistribution->map(function ($row) {
            if (($row->label ?? '') === 'active') return __('pages/home.activation_active');
            if (($row->label ?? '') === 'inactive') return __('pages/home.activation_inactive');
            return $row->label ?? __('pages/home.not_provided');
        })->values()->all();
        $activationTotals = $activationDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $roleLabels = $roleDistribution->map(fn ($row) => $row->role ?? $notProvided)->values()->all();
        $roleTotals = $roleDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $carrierLabels = $usersPerCarrier->map(fn ($row) => $row->carrier?->name ?? $notProvided)->values()->all();
        $carrierTotals = $usersPerCarrier->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $carrierPresenceLabels = [
            __('pages/reports.users_with_carrier'),
            __('pages/reports.users_without_carrier'),
        ];
        $carrierPresenceTotals = [
            (int) ($stats['usersWithCarrierCount'] ?? 0),
            (int) ($stats['usersWithoutCarrierCount'] ?? 0),
        ];

        $activationChart = [
            'type' => 'doughnut',
            'data' => [
                'labels' => $activationLabels,
                'datasets' => [[ 'data' => $activationTotals ]],
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

        $carrierPresenceChart = [
            'type' => 'pie',
            'data' => [
                'labels' => $carrierPresenceLabels,
                'datasets' => [[ 'data' => $carrierPresenceTotals, 'borderWidth' => 1 ]],
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
    @endphp

    <div class="card shadow-sm border-0 home-hero mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
                <div>
                    <span class="badge bg-light text-dark home-hero-badge mb-2">{{ __('pages/reports.users_badge') }}</span>
                    <h2 class="fw-bold mb-1">{{ __('pages/reports.users_title') }}</h2>
                    <p class="mb-0 opacity-75">{{ __('pages/reports.users_subtitle') }}</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-white text-dark shadow-sm">{{ __('pages/reports.kpi_total') }}: {{ number_format($stats['totalUsers'] ?? 0) }}</span>
                    <span class="badge bg-white text-dark shadow-sm">{{ __('pages/home.activation_active') }}: {{ number_format($stats['activeUsers'] ?? 0) }}</span>
                    <span class="badge bg-white text-dark shadow-sm">{{ __('pages/home.activation_inactive') }}: {{ number_format($stats['inactiveUsers'] ?? 0) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 h-100 ltm-kpi-card ltm-kpi-card--teal">
                <div class="card-body">
                    <p class="ltm-kpi-label mb-1">{{ __('pages/reports.users_with_carrier') }}</p>
                    <h3 class="fw-bold ltm-kpi-value mb-0">{{ number_format($stats['usersWithCarrierCount'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 h-100 ltm-kpi-card ltm-kpi-card--amber">
                <div class="card-body">
                    <p class="ltm-kpi-label mb-1">{{ __('pages/reports.users_without_carrier') }}</p>
                    <h3 class="fw-bold ltm-kpi-value mb-0">{{ number_format($stats['usersWithoutCarrierCount'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 h-100 ltm-kpi-card ltm-kpi-card--violet">
                <div class="card-body">
                    <p class="ltm-kpi-label mb-1">{{ __('pages/reports.users_roles') }}</p>
                    <h3 class="fw-bold ltm-kpi-value mb-0">{{ number_format(count($roleLabels)) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 h-100 ltm-kpi-card ltm-kpi-card--cobalt">
                <div class="card-body">
                    <p class="ltm-kpi-label mb-1">{{ __('pages/reports.users_carriers') }}</p>
                    <h3 class="fw-bold ltm-kpi-value mb-0">{{ number_format(count($carrierLabels)) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 home-charts-row">
        <div class="col-lg-4">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.users_activation_breakdown') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="260" data-chart-config='@json($activationChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.users_role_breakdown') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="260" data-chart-config='@json($rolesChart)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3 home-charts-row">
        <div class="col-lg-5">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.users_carrier_presence') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="290" data-chart-config='@json($carrierPresenceChart)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card home-chart-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('pages/reports.users_carrier_breakdown') }}</h6>
                </div>
                <div class="card-body">
                    <canvas height="290" data-chart-config='@json($perCarrierChart)'></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

