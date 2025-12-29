@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('participant/offers.index_title'),
        'subtitle' => __('participant/offers.index_subtitle'),
        'buttonLabel' => __('participant/offers.add_button'),
        'buttonRoute' => route('participant.oferte.create'),
        'badges' => [
            __('participant/offers.badge_rule'),
        ],
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('participant.oferte.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label mb-1">{{ __('participant/offers.filter_auction') }}</label>
                    <select name="auction_id" class="form-select">
                        <option value="">{{ __('participant/offers.all') }}</option>
                        @foreach($auctions as $auction)
                            <option value="{{ $auction->id }}" @selected(($filters['auction_id'] ?? '') == $auction->id)>{{ $auction->auction_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-1">{{ __('participant/offers.filter_lot') }}</label>
                    <select name="lot_id" class="form-select">
                        <option value="">{{ __('participant/offers.all') }}</option>
                        @foreach($lots as $lot)
                            <option value="{{ $lot->id }}" @selected(($filters['lot_id'] ?? '') == $lot->id)>{{ $lot->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button class="btn btn-primary text-white" type="submit">
                        <i class="fa-solid fa-filter me-1"></i> {{ __('ltm/common.filter') }}
                    </button>
                    <a href="{{ route('participant.oferte.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.reset') }}</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('participant/offers.col_auction') }}</th>
                            <th>{{ __('participant/offers.col_lot') }}</th>
                            <th>{{ __('participant/offers.col_price_trip') }}</th>
                            <th>{{ __('participant/offers.col_price_ton') }}</th>
                            <th>{{ __('participant/offers.col_auction_status') }}</th>
                            <th class="text-end">{{ __('ltm/common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bids as $bid)
                            <tr>
                                <td>{{ $bid->id }}</td>
                                <td>{{ $bid->auction->auction_number ?? '-' }}</td>
                                <td>{{ $bid->lot->code ?? '-' }}</td>
                                <td>{{ $bid->price_per_trip_eur !== null ? number_format((float)$bid->price_per_trip_eur, 2, ',', '.') . ' ' . ($bid->currency->code ?? '') : '-' }}</td>
                                <td>{{ $bid->price_per_ton_eur !== null ? number_format((float)$bid->price_per_ton_eur, 2, ',', '.') . ' ' . ($bid->currency->code ?? '') : '-' }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $bid->auction->status ?? '-' }}</span>
                                </td>
                                <td class="text-end">
                                    @if(($bid->auction->status ?? null) === 'deschisă')
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('participant.oferte.edit', $bid) }}">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('participant/offers.edit') }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">{{ __('participant/offers.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($bids, 'links'))
            <div class="card-footer bg-white">
                {{ $bids->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
