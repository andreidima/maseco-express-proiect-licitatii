@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $currencyBadges = [];
        foreach (($stats['averageTripByCurrency'] ?? []) as $row) {
            $currencyBadges[] = __('ltm/bids.badge_avg_trip', [
                'amount' => number_format((float)($row['avg'] ?? 0), 2, ',', '.'),
                'currency' => $row['code'] ?? '',
            ]);
        }
    @endphp
    @include('ltm.partials.header', [
        'title' => __('ltm/bids.title'),
        'subtitle' => __('ltm/bids.subtitle'),
        'buttonLabel' => __('ltm/bids.add_button'),
        'buttonRoute' => route('ltm.oferte.create'),
        'badges' => array_merge([
            __('ltm/bids.badge_total', ['count' => $stats['total'] ?? 0]),
            __('ltm/bids.badge_accepted', ['count' => $stats['accepted'] ?? 0]),
        ], $currencyBadges),
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('ltm.oferte.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/bids.filter_auction') }}</label>
                    <select name="auction_id" class="form-select">
                        <option value="">{{ __('ltm/bids.option_choose_auction') }}</option>
                        @foreach($auctions as $auction)
                            <option value="{{ $auction->id }}" @selected(($filters['auction_id'] ?? '') == $auction->id)>{{ $auction->auction_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/bids.filter_lot') }}</label>
                    <select name="lot_id" class="form-select">
                        <option value="">{{ __('ltm/bids.option_choose_lot') }}</option>
                        @foreach($lots as $lot)
                            <option value="{{ $lot->id }}" @selected(($filters['lot_id'] ?? '') == $lot->id)>{{ $lot->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/bids.filter_carrier') }}</label>
                    <select name="carrier_id" class="form-select">
                        <option value="">{{ __('ltm/bids.option_choose_carrier') }}</option>
                        @foreach($carriers as $carrier)
                            <option value="{{ $carrier->id }}" @selected(($filters['carrier_id'] ?? '') == $carrier->id)>{{ $carrier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/bids.filter_status') }}</label>
                    <select name="status" class="form-select">
                        <option value="">{{ __('ltm/bids.option_select') }}</option>
                        @foreach($statusOptions as $status)
                            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/bids.filter_price_trip_min') }}</label>
                    <input type="number" step="0.01" name="price_trip_min" value="{{ $filters['price_trip_min'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/bids.filter_price_trip_max') }}</label>
                    <input type="number" step="0.01" name="price_trip_max" value="{{ $filters['price_trip_max'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/bids.filter_price_ton_min') }}</label>
                    <input type="number" step="0.01" name="price_ton_min" value="{{ $filters['price_ton_min'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/bids.filter_price_ton_max') }}</label>
                    <input type="number" step="0.01" name="price_ton_max" value="{{ $filters['price_ton_max'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/bids.filter_payment_terms_min') }}</label>
                    <input type="number" name="payment_terms_min" value="{{ $filters['payment_terms_min'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/bids.filter_payment_terms_max') }}</label>
                    <input type="number" name="payment_terms_max" value="{{ $filters['payment_terms_max'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary text-white" type="submit">
                        <i class="fa-solid fa-filter me-1"></i> {{ __('ltm/common.filter') }}
                    </button>
                    <a href="{{ route('ltm.oferte.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.reset') }}</a>
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
                            <th>{{ __('ltm/bids.col_auction') }}</th>
                            <th>{{ __('ltm/bids.col_lot') }}</th>
                            <th>{{ __('ltm/bids.col_carrier') }}</th>
                            <th>{{ __('ltm/bids.col_price_trip') }}</th>
                            <th>{{ __('ltm/bids.col_price_ton') }}</th>
                            <th>{{ __('ltm/bids.col_payment_terms') }}</th>
                            <th>{{ __('ltm/bids.col_status') }}</th>
                            <th class="text-end">{{ __('ltm/common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bids as $bid)
                            <tr>
                                <td>{{ ($bids->currentPage() - 1) * $bids->perPage() + $loop->iteration }}</td>
                                <td>{{ $bid->auction->auction_number ?? '-' }}</td>
                                <td>{{ $bid->lot->code ?? '-' }}</td>
                                <td>{{ $bid->carrier->name ?? '-' }}</td>
                                <td>{{ number_format((float)$bid->price_per_trip_eur, 2, ',', '.') }} {{ $bid->currency->code ?? '' }}</td>
                                <td>{{ $bid->price_per_ton_eur ? number_format($bid->price_per_ton_eur, 2, ',', '.') . ' ' . ($bid->currency->code ?? '') : '-' }}</td>
                                <td>{{ $bid->payment_terms_days }}</td>
                                <td><span class="badge bg-info text-dark">{{ $bid->status }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('ltm.oferte.edit', $bid) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('ltm/common.edit') }}
                                    </a>
                                    <form action="{{ route('ltm.oferte.destroy', $bid) }}" method="POST" class="d-inline">
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
                                <td colspan="9" class="text-center py-4 text-muted">{{ __('ltm/bids.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $bids->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
