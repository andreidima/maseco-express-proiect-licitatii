@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $myBidStatusDistribution = collect($stats['participantMyBidStatusDistribution'] ?? []);
        $mySupportStatusDistribution = collect($stats['participantMySupportStatusDistribution'] ?? []);

        $notProvided = __('pages/home.not_provided');

        $myBidStatusLabels = $myBidStatusDistribution->map(fn ($row) => $row->status ?? $notProvided)->values()->all();
        $myBidStatusTotals = $myBidStatusDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $mySupportStatusLabels = $mySupportStatusDistribution->map(fn ($row) => ucfirst($row->status ?? $notProvided))->values()->all();
        $mySupportStatusTotals = $mySupportStatusDistribution->pluck('total')->map(fn ($v) => (int) $v)->values()->all();

        $myBidStatusChart = [
            'type' => 'doughnut',
            'data' => [
                'labels' => $myBidStatusLabels,
                'datasets' => [[ 'data' => $myBidStatusTotals ]],
            ],
        ];

        $mySupportStatusChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $mySupportStatusLabels,
                'datasets' => [[ 'data' => $mySupportStatusTotals, 'borderRadius' => 10 ]],
            ],
            'options' => [
                'plugins' => ['legend' => ['display' => false]],
                'scales' => ['y' => ['beginAtZero' => true]],
            ],
        ];
    @endphp

    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ __('participant/home.title') }}</h5>
                        <p class="mb-0 text-muted">{!! __('participant/home.welcome', ['name' => e(Auth::user()->name)]) !!}</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a class="btn btn-primary" href="{{ route('participant.licitatii.index') }}">
                            <i class="fa-solid fa-gavel me-1"></i> {{ __('participant/home.view_auctions') }}
                        </a>
                        <a class="btn btn-outline-primary" href="{{ route('participant.oferte.index') }}">
                            <i class="fa-solid fa-tags me-1"></i> {{ __('participant/home.my_offers') }}
                        </a>
                        <a class="btn btn-outline-secondary" href="{{ route('participant.support.index') }}">
                            <i class="fa-solid fa-comments me-1"></i> {{ __('support.nav_participant') }}
                        </a>
                        <a class="btn btn-outline-secondary" href="{{ route('participant.profil.edit') }}">
                            <i class="fa-solid fa-id-card me-1"></i> {{ __('participant/home.profile') }}
                        </a>
                    </div>
                </div>
            </div>

            @include('errors.errors')

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100 ltm-kpi-card ltm-kpi-card--teal">
                        <div class="card-body">
                            <p class="ltm-kpi-label mb-1">{{ __('participant/home.kpi_open_auctions') }}</p>
                            <h3 class="fw-bold ltm-kpi-value mb-0">{{ number_format($stats['participantOpenAuctions'] ?? 0) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100 ltm-kpi-card ltm-kpi-card--blue">
                        <div class="card-body">
                            <p class="ltm-kpi-label mb-1">{{ __('participant/home.kpi_my_offers') }}</p>
                            <h3 class="fw-bold ltm-kpi-value mb-0">{{ number_format($stats['participantCarrierBidCount'] ?? 0) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100 ltm-kpi-card ltm-kpi-card--amber">
                        <div class="card-body">
                            <p class="ltm-kpi-label mb-1">{{ __('participant/home.kpi_my_support_threads') }}</p>
                            <h3 class="fw-bold ltm-kpi-value mb-0">{{ number_format($stats['participantSupportThreadCount'] ?? 0) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-lg-5">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0">
                            <h6 class="mb-0">{{ __('participant/home.chart_my_offers_by_status') }}</h6>
                        </div>
                        <div class="card-body">
                            <canvas height="260" data-chart-config='@json($myBidStatusChart)'></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0">
                            <h6 class="mb-0">{{ __('participant/home.chart_my_support_by_status') }}</h6>
                        </div>
                        <div class="card-body">
                            <canvas height="260" data-chart-config='@json($mySupportStatusChart)'></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
