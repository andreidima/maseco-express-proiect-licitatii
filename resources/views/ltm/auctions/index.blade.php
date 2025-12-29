@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $currencyBadges = [];
        foreach (($stats['estTotals'] ?? []) as $row) {
            $currencyBadges[] = __('ltm/auctions.badge_est_total', [
                'value' => number_format((float)($row['total'] ?? 0), 0, ',', '.'),
                'currency' => $row['code'] ?? '',
            ]);
        }
    @endphp
    @include('ltm.partials.header', [
        'title' => __('ltm/auctions.index_title'),
        'subtitle' => __('ltm/auctions.index_subtitle'),
        'buttonLabel' => __('ltm/auctions.add_button'),
        'buttonRoute' => route('ltm.licitatii.create'),
        'badges' => array_merge([
            __('ltm/auctions.badge_total', ['count' => ($stats['total'] ?? 0)]),
            __('ltm/auctions.badge_open', ['count' => ($stats['open'] ?? 0)]),
            __('ltm/auctions.badge_awarded', ['count' => ($stats['awarded'] ?? 0)]),
        ], $currencyBadges),
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('ltm.licitatii.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/auctions.filter_auction_number') }}</label>
                    <input type="text" name="auction_number" value="{{ $filters['auction_number'] ?? '' }}" class="form-control" placeholder="LTM-0001">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/auctions.filter_title') }}</label>
                    <input type="text" name="title" value="{{ $filters['title'] ?? '' }}" class="form-control" placeholder="{{ __('ltm/auctions.placeholder_title') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/auctions.filter_client') }}</label>
                    <select name="client_id" class="form-select">
                        <option value="">{{ __('ltm/auctions.choose_client') }}</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected(($filters['client_id'] ?? '') == $client->id)>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/auctions.filter_route') }}</label>
                    <select name="route_id" class="form-select">
                        <option value="">{{ __('ltm/auctions.choose_route') }}</option>
                        @foreach($routes as $route)
                            <option value="{{ $route->id }}" @selected(($filters['route_id'] ?? '') == $route->id)>{{ $route->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/auctions.filter_type') }}</label>
                    <select name="type" class="form-select">
                        <option value="">{{ __('ltm/auctions.choose') }}</option>
                        @foreach($typeOptions as $option)
                            <option value="{{ $option }}" @selected(($filters['type'] ?? '') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/auctions.filter_status') }}</label>
                    <select name="status" class="form-select">
                        <option value="">{{ __('ltm/auctions.choose') }}</option>
                        @foreach($statusOptions as $option)
                            <option value="{{ $option }}" @selected(($filters['status'] ?? '') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/auctions.filter_estimated_min') }}</label>
                    <input type="number" name="estimated_value_min" value="{{ $filters['estimated_value_min'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/auctions.filter_estimated_max') }}</label>
                    <input type="number" name="estimated_value_max" value="{{ $filters['estimated_value_max'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/auctions.filter_total_lots_min') }}</label>
                    <input type="number" name="total_lots_min" value="{{ $filters['total_lots_min'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/auctions.filter_total_lots_max') }}</label>
                    <input type="number" name="total_lots_max" value="{{ $filters['total_lots_max'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/auctions.filter_volume_min') }}</label>
                    <input type="number" step="0.1" name="expected_volume_min" value="{{ $filters['expected_volume_min'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/auctions.filter_volume_max') }}</label>
                    <input type="number" step="0.1" name="expected_volume_max" value="{{ $filters['expected_volume_max'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary text-white" type="submit">
                        <i class="fa-solid fa-filter me-1"></i> {{ __('ltm/common.filter') }}
                    </button>
                    <a href="{{ route('ltm.licitatii.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.reset') }}</a>
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
                            <th>{{ __('ltm/auctions.col_number') }}</th>
                            <th>{{ __('ltm/auctions.col_title') }}</th>
                            <th>{{ __('ltm/auctions.col_client') }}</th>
                            <th>{{ __('ltm/auctions.col_route') }}</th>
                            <th>{{ __('ltm/auctions.col_type') }}</th>
                            <th>{{ __('ltm/auctions.col_status') }}</th>
                            <th>{{ __('ltm/auctions.col_estimated_value') }}</th>
                            <th>{{ __('ltm/auctions.col_lots') }}</th>
                            <th class="text-end">{{ __('ltm/common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
        @forelse($auctions as $auction)
            <tr>
                                <td>{{ ($auctions->currentPage() - 1) * $auctions->perPage() + $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $auction->auction_number }}</td>
                                <td>{{ $auction->title }}</td>
                                <td>{{ $auction->client->name ?? '-' }}</td>
                                <td>{{ $auction->route->code ?? '-' }}</td>
                                <td><span class="badge bg-info text-dark">{{ $auction->type }}</span></td>
                                <td><span class="badge bg-secondary">{{ $auction->status }}</span></td>
                <td>
                    {{ $auction->estimated_value_eur ? number_format($auction->estimated_value_eur, 0, ',', '.') . ' ' . ($auction->currency->code ?? '') : '-' }}
                </td>
                <td>{{ $auction->total_lots }}</td>
                                <td class="text-end">
                                    <a href="{{ route('ltm.licitatii.edit', $auction) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('ltm/common.edit') }}
                                    </a>
                                    <form action="{{ route('ltm.licitatii.destroy', $auction) }}" method="POST" class="d-inline">
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
                                <td colspan="10" class="text-center py-4 text-muted">{{ __('ltm/auctions.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $auctions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
