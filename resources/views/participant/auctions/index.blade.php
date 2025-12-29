@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('participant/auctions.index_title'),
        'subtitle' => __('participant/auctions.index_subtitle'),
        'badges' => [
            __('participant/auctions.badge'),
        ],
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('participant.licitatii.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('participant/auctions.filter_auction_number') }}</label>
                    <input type="text" name="auction_number" value="{{ $filters['auction_number'] ?? '' }}" class="form-control" placeholder="LTM-0001">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('participant/auctions.filter_title') }}</label>
                    <input type="text" name="title" value="{{ $filters['title'] ?? '' }}" class="form-control" placeholder="{{ __('participant/auctions.placeholder_title') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('participant/auctions.filter_client') }}</label>
                    <select name="client_id" class="form-select">
                        <option value="">{{ __('participant/auctions.choose_client') }}</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected(($filters['client_id'] ?? '') == $client->id)>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('participant/auctions.filter_route') }}</label>
                    <select name="route_id" class="form-select">
                        <option value="">{{ __('participant/auctions.choose_route') }}</option>
                        @foreach($routes as $route)
                            <option value="{{ $route->id }}" @selected(($filters['route_id'] ?? '') == $route->id)>{{ $route->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('participant/auctions.filter_status') }}</label>
                    <select name="status" class="form-select">
                        <option value="">{{ __('participant/auctions.choose') }}</option>
                        @foreach($statusOptions as $option)
                            <option value="{{ $option }}" @selected(($filters['status'] ?? '') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary text-white" type="submit">
                        <i class="fa-solid fa-filter me-1"></i> {{ __('ltm/common.filter') }}
                    </button>
                    <a href="{{ route('participant.licitatii.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.reset') }}</a>
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
                            <th>{{ __('participant/auctions.col_number') }}</th>
                            <th>{{ __('participant/auctions.col_title') }}</th>
                            <th>{{ __('participant/auctions.col_client') }}</th>
                            <th>{{ __('participant/auctions.col_route') }}</th>
                            <th>{{ __('participant/auctions.col_status') }}</th>
                            <th class="text-end">{{ __('participant/auctions.col_details') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auctions as $auction)
                            <tr>
                                <td>{{ $auction->id }}</td>
                                <td>{{ $auction->auction_number }}</td>
                                <td>{{ $auction->title }}</td>
                                <td>{{ $auction->client->name ?? '-' }}</td>
                                <td>{{ $auction->route->code ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $auction->status ?? '-' }}</span>
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('participant.licitatii.show', $auction) }}">
                                        <i class="fa-solid fa-eye me-1"></i> {{ __('participant/auctions.view') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">{{ __('participant/auctions.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($auctions, 'links'))
            <div class="card-footer bg-white">
                {{ $auctions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
