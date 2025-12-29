@extends('layouts.app')

@section('body-class', 'ltm-panou-body')
@section('main-class', 'ltm-panou-main')

@section('content')
<div class="container ltm-dashboard">
    @include('ltm.partials.header', [
        'title' => __('ltm/dashboard.header_title'),
        'subtitle' => __('ltm/dashboard.header_subtitle'),
        'buttonLabel' => __('ltm/dashboard.header_button'),
        'buttonRoute' => route('ltm.licitatii.index'),
        'badges' => [
            __('ltm/dashboard.badge_module'),
            __('ltm/dashboard.badge_realtime'),
        ],
    ])
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100 ltm-kpi-card ltm-kpi-card--teal">
                <div class="card-body">
                    <p class="ltm-kpi-label mb-1">{{ __('ltm/dashboard.kpi_total_auctions') }}</p>
                    <h3 class="fw-bold ltm-kpi-value mb-0">{{ $kpis['totalAuctions'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 ltm-kpi-card ltm-kpi-card--blue">
                <div class="card-body">
                    <p class="ltm-kpi-label mb-1">{{ __('ltm/dashboard.kpi_open_auctions') }}</p>
                    <h3 class="fw-bold ltm-kpi-value mb-0">{{ $kpis['openAuctions'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 ltm-kpi-card ltm-kpi-card--green">
                <div class="card-body">
                    <p class="ltm-kpi-label mb-1">{{ __('ltm/dashboard.kpi_awarded_auctions') }}</p>
                    <h3 class="fw-bold ltm-kpi-value mb-0">{{ $kpis['awardedAuctions'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 ltm-kpi-card ltm-kpi-card--amber">
                <div class="card-body">
                    <p class="ltm-kpi-label mb-1">{{ __('ltm/dashboard.kpi_estimated_value') }}</p>
                    @forelse(($kpis['estimatedValueByCurrency'] ?? []) as $row)
                        <h3 class="fw-bold ltm-kpi-value mb-0">{{ number_format((float)($row['total'] ?? 0), 0, ',', '.') }} {{ $row['code'] ?? '' }}</h3>
                    @empty
                        <h3 class="fw-bold ltm-kpi-value mb-0">0</h3>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 ltm-kpi-card ltm-kpi-card--cobalt">
                <div class="card-body">
                    <p class="ltm-kpi-label mb-1">{{ __('ltm/dashboard.kpi_contracted_value') }}</p>
                    @forelse(($kpis['contractedValueByCurrency'] ?? []) as $row)
                        <h3 class="fw-bold ltm-kpi-value mb-0">{{ number_format((float)($row['total'] ?? 0), 0, ',', '.') }} {{ $row['code'] ?? '' }}</h3>
                    @empty
                        <h3 class="fw-bold ltm-kpi-value mb-0">0</h3>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 ltm-kpi-card ltm-kpi-card--coral">
                <div class="card-body">
                    <p class="ltm-kpi-label mb-1">{{ __('ltm/dashboard.kpi_total_lots') }}</p>
                    <h3 class="fw-bold ltm-kpi-value mb-0">{{ $kpis['totalLots'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 ltm-kpi-card ltm-kpi-card--emerald">
                <div class="card-body">
                    <p class="ltm-kpi-label mb-1">{{ __('ltm/dashboard.kpi_active_clients') }}</p>
                    <h3 class="fw-bold ltm-kpi-value mb-0">{{ $kpis['activeClients'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 ltm-kpi-card ltm-kpi-card--violet">
                <div class="card-body">
                    <p class="ltm-kpi-label mb-1">{{ __('ltm/dashboard.kpi_active_carriers') }}</p>
                    <h3 class="fw-bold ltm-kpi-value mb-0">{{ $kpis['activeCarriers'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">{{ __('ltm/dashboard.top_clients_title') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('ltm/dashboard.col_client') }}</th>
                                <th class="text-end">{{ __('ltm/dashboard.col_value_eur') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topClients as $item)
                                <tr>
                                    <td>{{ $item->client->name ?? '-' }}</td>
                                    <td class="text-end">{{ number_format((float)$item->total_value, 0, ',', '.') }} {{ $item->currency->code ?? '' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-muted text-center">{{ __('ltm/common.no_data') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">{{ __('ltm/dashboard.top_carriers_title') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('ltm/dashboard.col_carrier') }}</th>
                                <th class="text-end">{{ __('ltm/dashboard.col_value_eur') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topCarriers as $item)
                                <tr>
                                    <td>{{ $item->carrier->name ?? '-' }}</td>
                                    <td class="text-end">{{ number_format((float)$item->total_value, 0, ',', '.') }} {{ $item->currency->code ?? '' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-muted text-center">{{ __('ltm/common.no_data') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('ltm/dashboard.status_distribution') }}</h6>
                </div>
                <div class="card-body">
                    @forelse($auctionStatus as $row)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-secondary">{{ $row->status ?? 'N/A' }}</span>
                            <span class="fw-bold">{{ $row->total }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">{{ __('ltm/common.no_data') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('ltm/dashboard.goods_types') }}</h6>
                </div>
                <div class="card-body">
                    @forelse($lotGoods as $row)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-info text-dark">{{ $row->goods_type ?? 'Nespecificat' }}</span>
                            <span class="fw-bold">{{ $row->total }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">{{ __('ltm/common.no_data') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">{{ __('ltm/dashboard.destination_countries') }}</h6>
                </div>
                <div class="card-body">
                    @forelse($destinationCountries as $row)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-warning text-dark">{{ $row->destination_country ?? 'N/A' }}</span>
                            <span class="fw-bold">{{ $row->total }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">{{ __('ltm/common.no_data') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h6 class="mb-3">{{ __('ltm/dashboard.operational_averages') }}</h6>
                    <div class="d-flex gap-3 flex-wrap">
                        <span class="badge bg-primary">{!! __('ltm/dashboard.avg_weight_per_lot', ['value' => number_format((float)$averages['lotWeight'], 1)]) !!}</span>
                        <span class="badge bg-success">{!! __('ltm/dashboard.avg_distance_per_route', ['value' => number_format((float)$averages['routeDistance'], 1)]) !!}</span>
                    </div>
                    <h6 class="mt-4 mb-3">{{ __('ltm/dashboard.bid_status') }}</h6>
                    @forelse($bidStatus as $row)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-light text-dark">{{ $row->status ?? 'N/A' }}</span>
                            <span class="fw-bold">{{ $row->total }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">{{ __('ltm/dashboard.no_bids') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h6 class="mb-3">{{ __('ltm/dashboard.document_types') }}</h6>
                    @forelse($documentsByType as $row)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-secondary">{{ $row->type ?? 'N/A' }}</span>
                            <span class="fw-bold">{{ $row->total }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">{{ __('ltm/dashboard.no_documents') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">{{ __('ltm/dashboard.top_auctions_title') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('ltm/dashboard.col_auction') }}</th>
                                <th>{{ __('ltm/dashboard.col_client') }}</th>
                                <th class="text-end">{{ __('ltm/dashboard.col_estimated_value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topAuctionsByValue as $auction)
                                <tr>
                                    <td>{{ $auction->auction_number }}</td>
                                    <td>{{ $auction->client->name ?? '-' }}</td>
                                    <td class="text-end">{{ number_format((float)$auction->estimated_value_eur, 0, ',', '.') }} {{ $auction->currency->code ?? '' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-muted text-center">{{ __('ltm/common.no_data') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">{{ __('ltm/dashboard.top_contracts_title') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('ltm/dashboard.col_contract') }}</th>
                                <th>{{ __('ltm/dashboard.col_client') }}</th>
                                <th>{{ __('ltm/dashboard.col_carrier') }}</th>
                                <th class="text-end">{{ __('ltm/dashboard.col_value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topContractsByValue as $contract)
                                <tr>
                                    <td>{{ $contract->contract_number }}</td>
                                    <td>{{ $contract->client->name ?? '-' }}</td>
                                    <td>{{ $contract->carrier->name ?? '-' }}</td>
                                    <td class="text-end">{{ number_format((float)$contract->total_value_eur, 0, ',', '.') }} {{ $contract->currency->code ?? '' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted text-center">{{ __('ltm/common.no_data') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
