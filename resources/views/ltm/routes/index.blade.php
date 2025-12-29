@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/routes.title'),
        'subtitle' => __('ltm/routes.subtitle'),
        'buttonLabel' => __('ltm/routes.add_button'),
        'buttonRoute' => route('ltm.curse.create'),
        'badges' => [
            __('ltm/routes.badge_total', ['count' => $stats['total'] ?? 0]),
            __('ltm/routes.badge_with_auctions', ['count' => $stats['withAuctions'] ?? 0]),
            __('ltm/routes.badge_avg_distance', ['distance' => $stats['avgDistance'] ?? 0]),
        ],
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('ltm.curse.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/routes.filter_code') }}</label>
                    <input type="text" name="code" value="{{ $filters['code'] ?? '' }}" class="form-control" placeholder="CURS-0001">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/routes.filter_origin_city') }}</label>
                    <input type="text" name="origin_city" value="{{ $filters['origin_city'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/routes.filter_origin_country') }}</label>
                    <select name="origin_country" class="form-select">
                        <option value="">{{ __('ltm/routes.option_select') }}</option>
                        @foreach($countries as $country)
                            <option value="{{ $country }}" @selected(($filters['origin_country'] ?? '') === $country)>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/routes.filter_destination_city') }}</label>
                    <input type="text" name="destination_city" value="{{ $filters['destination_city'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/routes.filter_destination_country') }}</label>
                    <select name="destination_country" class="form-select">
                        <option value="">{{ __('ltm/routes.option_select') }}</option>
                        @foreach($destinationCountries as $country)
                            <option value="{{ $country }}" @selected(($filters['destination_country'] ?? '') === $country)>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/routes.filter_typical_goods') }}</label>
                    <input type="text" name="typical_goods" value="{{ $filters['typical_goods'] ?? '' }}" class="form-control" placeholder="{{ __('ltm/routes.filter_typical_goods_placeholder') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/routes.filter_distance_min') }}</label>
                    <input type="number" name="distance_min" value="{{ $filters['distance_min'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/routes.filter_distance_max') }}</label>
                    <input type="number" name="distance_max" value="{{ $filters['distance_max'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/routes.filter_weight_min') }}</label>
                    <input type="number" step="0.1" name="weight_min" value="{{ $filters['weight_min'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/routes.filter_weight_max') }}</label>
                    <input type="number" step="0.1" name="weight_max" value="{{ $filters['weight_max'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary text-white" type="submit">
                        <i class="fa-solid fa-filter me-1"></i> {{ __('ltm/common.filter') }}
                    </button>
                    <a href="{{ route('ltm.curse.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.reset') }}</a>
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
                            <th>{{ __('ltm/routes.col_code') }}</th>
                            <th>{{ __('ltm/routes.col_origin') }}</th>
                            <th>{{ __('ltm/routes.col_destination') }}</th>
                            <th>{{ __('ltm/routes.col_distance') }}</th>
                            <th>{{ __('ltm/routes.col_typical_goods') }}</th>
                            <th>{{ __('ltm/routes.col_avg_weight') }}</th>
                            <th class="text-end">{{ __('ltm/common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($routes as $route)
                            <tr>
                                <td>{{ ($routes->currentPage() - 1) * $routes->perPage() + $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $route->code }}</td>
                                <td>{{ $route->origin_city }} ({{ $route->origin_country }})</td>
                                <td>{{ $route->destination_city }} ({{ $route->destination_country }})</td>
                                <td>{{ $route->distance_km }}</td>
                                <td>{{ $route->typical_goods }}</td>
                                <td>{{ $route->average_weight_tons }}</td>
                                <td class="text-end">
                                    <a href="{{ route('ltm.curse.edit', $route) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('ltm/common.edit') }}
                                    </a>
                                    <form action="{{ route('ltm.curse.destroy', $route) }}" method="POST" class="d-inline">
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
                                <td colspan="8" class="text-center py-4 text-muted">{{ __('ltm/routes.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $routes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
