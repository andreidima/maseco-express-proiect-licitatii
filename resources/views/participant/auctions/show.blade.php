@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('participant/auctions.show_title'),
        'subtitle' => __('participant/auctions.show_subtitle'),
        'badges' => [
            __('participant/auctions.badge_status', ['status' => e($auction->status ?? '-')]),
            __('participant/auctions.badge_number', ['number' => e($auction->auction_number ?? '-')]),
        ],
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="text-muted small">{{ __('participant/auctions.field_title') }}</div>
                    <div class="fw-bold">{{ $auction->title ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">{{ __('participant/auctions.field_client') }}</div>
                    <div class="fw-bold">{{ $auction->client->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">{{ __('participant/auctions.field_route') }}</div>
                    <div class="fw-bold">{{ $auction->route->code ?? '-' }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted small">{{ __('participant/auctions.field_description') }}</div>
                    <div>{{ $auction->description ?? '-' }}</div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <a class="btn btn-outline-secondary" href="{{ route('participant.licitatii.index') }}">
                <i class="fa-solid fa-arrow-left me-1"></i> {{ __('participant/auctions.back_to_auctions') }}
            </a>
            <a class="btn btn-outline-primary" href="{{ route('participant.oferte.index') }}">
                <i class="fa-solid fa-tags me-1"></i> {{ __('participant/auctions.my_offers') }}
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h6 class="mb-0">{{ __('participant/auctions.lots') }}</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('participant/auctions.lot_code') }}</th>
                            <th>{{ __('participant/auctions.lot_description') }}</th>
                            <th>{{ __('participant/auctions.lot_goods_type') }}</th>
                            <th>{{ __('participant/auctions.lot_weight') }}</th>
                            <th class="text-end">{{ __('participant/auctions.my_bid') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lots as $lot)
                            @php
                                $myBid = $existingBids->get($lot->id);
                            @endphp
                            <tr>
                                <td class="fw-bold">{{ $lot->code ?? '-' }}</td>
                                <td>{{ $lot->description ?? '-' }}</td>
                                <td>{{ $lot->goods_type ?? '-' }}</td>
                                <td>{{ $lot->weight_tons ? number_format((float)$lot->weight_tons, 2, ',', '.') . ' t' : '-' }}</td>
                                <td class="text-end">
                                    @if(empty(Auth::user()->carrier_id))
                                        <span class="text-danger">{{ __('participant/auctions.account_not_linked') }}</span>
                                    @else
                                    @if($myBid)
                                        <span class="badge bg-success me-2">{{ __('participant/auctions.bid_added') }}</span>
                                        @if(($auction->status ?? null) === 'deschisă')
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('participant.oferte.edit', $myBid) }}">
                                                <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('participant/auctions.edit') }}
                                            </a>
                                        @endif
                                    @else
                                        @if(($auction->status ?? null) === 'deschisă')
                                            <a class="btn btn-sm btn-primary text-white" href="{{ route('participant.oferte.create', ['lot_id' => $lot->id]) }}">
                                                <i class="fa-solid fa-plus me-1"></i> {{ __('participant/auctions.add_offer') }}
                                            </a>
                                        @else
                                            <span class="text-muted">{{ __('participant/auctions.unavailable') }}</span>
                                        @endif
                                    @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">{{ __('participant/auctions.no_lots') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
