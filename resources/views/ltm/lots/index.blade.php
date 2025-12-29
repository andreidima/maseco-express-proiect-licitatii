@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/lots.title'),
        'subtitle' => __('ltm/lots.subtitle'),
        'buttonLabel' => __('ltm/lots.add_button'),
        'buttonRoute' => route('ltm.loturi.create'),
        'badges' => [
            __('ltm/lots.badge_total', ['count' => $stats['total'] ?? 0]),
            __('ltm/lots.badge_avg_weight', ['amount' => $stats['avgWeight'] ?? 0]),
            __('ltm/lots.badge_avg_trips', ['count' => $stats['avgTrips'] ?? 0]),
        ],
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('ltm.loturi.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/lots.filter_code') }}</label>
                    <input type="text" name="code" value="{{ $filters['code'] ?? '' }}" class="form-control" placeholder="LOT-0001">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/lots.filter_auction') }}</label>
                    <select name="auction_id" class="form-select">
                        <option value="">{{ __('ltm/lots.option_choose_auction') }}</option>
                        @foreach($auctions as $auction)
                            <option value="{{ $auction->id }}" @selected(($filters['auction_id'] ?? '') == $auction->id)>{{ $auction->auction_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/lots.filter_goods_type') }}</label>
                    <select name="goods_type" class="form-select">
                        <option value="">{{ __('ltm/lots.option_select') }}</option>
                        @foreach($goodsTypes as $type)
                            <option value="{{ $type }}" @selected(($filters['goods_type'] ?? '') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/lots.filter_pickup_city') }}</label>
                    <input type="text" name="pickup_city" value="{{ $filters['pickup_city'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/lots.filter_delivery_city') }}</label>
                    <input type="text" name="delivery_city" value="{{ $filters['delivery_city'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/lots.filter_weight_min') }}</label>
                    <input type="number" step="0.1" name="weight_min" value="{{ $filters['weight_min'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/lots.filter_weight_max') }}</label>
                    <input type="number" step="0.1" name="weight_max" value="{{ $filters['weight_max'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/lots.filter_pallets_min') }}</label>
                    <input type="number" name="pallets_min" value="{{ $filters['pallets_min'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/lots.filter_pallets_max') }}</label>
                    <input type="number" name="pallets_max" value="{{ $filters['pallets_max'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/lots.filter_trips_min') }}</label>
                    <input type="number" name="trips_min" value="{{ $filters['trips_min'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/lots.filter_trips_max') }}</label>
                    <input type="number" name="trips_max" value="{{ $filters['trips_max'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/lots.filter_budget_min') }}</label>
                    <input type="number" name="budget_min" value="{{ $filters['budget_min'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/lots.filter_budget_max') }}</label>
                    <input type="number" name="budget_max" value="{{ $filters['budget_max'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary text-white" type="submit">
                        <i class="fa-solid fa-filter me-1"></i> {{ __('ltm/common.filter') }}
                    </button>
                    <a href="{{ route('ltm.loturi.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.reset') }}</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 ltm-records-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('ltm/lots.col_code') }}</th>
                            <th>{{ __('ltm/lots.col_auction') }}</th>
                            <th>{{ __('ltm/lots.col_goods_type') }}</th>
                            <th>{{ __('ltm/lots.col_weight') }}</th>
                            <th>{{ __('ltm/lots.col_pallets') }}</th>
                            <th>{{ __('ltm/lots.col_trips') }}</th>
                            <th>{{ __('ltm/lots.col_budget_max') }}</th>
                            <th>{{ __('ltm/lots.col_pickup') }}</th>
                            <th>{{ __('ltm/lots.col_delivery') }}</th>
                            <th class="text-end">{{ __('ltm/common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lots as $lot)
                            <tr>
                                <td>{{ ($lots->currentPage() - 1) * $lots->perPage() + $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $lot->code }}</td>
                                <td>{{ $lot->auction->auction_number ?? '-' }}</td>
                                <td>{{ $lot->goods_type }}</td>
                                <td>{{ $lot->weight_tons }}</td>
                                <td>{{ $lot->pallets }}</td>
                                <td>{{ $lot->trips_per_month }}</td>
                                <td>
                                    {{ $lot->max_budget_eur ? number_format($lot->max_budget_eur, 0, ',', '.') . ' ' . ($lot->currency->code ?? '') : '-' }}
                                </td>
                                <td>{{ $lot->pickup_city }} ({{ $lot->pickup_country }})</td>
                                <td>{{ $lot->delivery_city }} ({{ $lot->delivery_country }})</td>
                                <td class="text-end">
                                    <a href="{{ route('ltm.loturi.edit', $lot) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('ltm/common.edit') }}
                                    </a>
                                    <form action="{{ route('ltm.loturi.destroy', $lot) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa-solid fa-trash me-1"></i> {{ __('ltm/common.delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">{{ __('ltm/lots.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $lots->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
