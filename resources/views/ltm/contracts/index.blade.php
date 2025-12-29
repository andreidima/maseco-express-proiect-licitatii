@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $currencyBadges = [];
        foreach (($stats['valueTotals'] ?? []) as $row) {
            $currencyBadges[] = __('ltm/contracts.badge_value_total', [
                'amount' => number_format((float)($row['total'] ?? 0), 0, ',', '.'),
                'currency' => $row['code'] ?? '',
            ]);
        }
    @endphp
    @include('ltm.partials.header', [
        'title' => __('ltm/contracts.title'),
        'subtitle' => __('ltm/contracts.subtitle'),
        'buttonLabel' => __('ltm/contracts.add_button'),
        'buttonRoute' => route('ltm.contracte.create'),
        'badges' => array_merge([
            __('ltm/contracts.badge_total', ['count' => $stats['total'] ?? 0]),
            __('ltm/contracts.badge_active', ['count' => $stats['active'] ?? 0]),
        ], $currencyBadges),
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('ltm.contracte.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/contracts.filter_contract_number') }}</label>
                    <input type="text" name="contract_number" value="{{ $filters['contract_number'] ?? '' }}" class="form-control" placeholder="CTR-LTM-0001">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/contracts.filter_auction') }}</label>
                    <select name="auction_id" class="form-select">
                        <option value="">{{ __('ltm/contracts.option_choose_auction') }}</option>
                        @foreach($auctions as $auction)
                            <option value="{{ $auction->id }}" @selected(($filters['auction_id'] ?? '') == $auction->id)>{{ $auction->auction_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/contracts.filter_carrier') }}</label>
                    <select name="carrier_id" class="form-select">
                        <option value="">{{ __('ltm/contracts.option_choose_carrier') }}</option>
                        @foreach($carriers as $carrier)
                            <option value="{{ $carrier->id }}" @selected(($filters['carrier_id'] ?? '') == $carrier->id)>{{ $carrier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/contracts.filter_client') }}</label>
                    <select name="client_id" class="form-select">
                        <option value="">{{ __('ltm/contracts.option_choose_client') }}</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected(($filters['client_id'] ?? '') == $client->id)>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/contracts.filter_contract_type') }}</label>
                    <select name="contract_type" class="form-select">
                        <option value="">{{ __('ltm/contracts.option_select') }}</option>
                        @foreach($contractTypes as $type)
                            <option value="{{ $type }}" @selected(($filters['contract_type'] ?? '') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/contracts.filter_status') }}</label>
                    <select name="status" class="form-select">
                        <option value="">{{ __('ltm/contracts.option_select') }}</option>
                        @foreach($statusOptions as $status)
                            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/contracts.filter_value_min') }}</label>
                    <input type="number" name="total_value_min" value="{{ $filters['total_value_min'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/contracts.filter_value_max') }}</label>
                    <input type="number" name="total_value_max" value="{{ $filters['total_value_max'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/contracts.filter_valid_from') }}</label>
                    <input type="date" name="valid_from" value="{{ $filters['valid_from'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/contracts.filter_valid_to') }}</label>
                    <input type="date" name="valid_to" value="{{ $filters['valid_to'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary text-white" type="submit">
                        <i class="fa-solid fa-filter me-1"></i> {{ __('ltm/common.filter') }}
                    </button>
                    <a href="{{ route('ltm.contracte.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.reset') }}</a>
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
                            <th>{{ __('ltm/contracts.col_number') }}</th>
                            <th>{{ __('ltm/contracts.col_auction') }}</th>
                            <th>{{ __('ltm/contracts.col_client') }}</th>
                            <th>{{ __('ltm/contracts.col_carrier') }}</th>
                            <th>{{ __('ltm/contracts.col_type') }}</th>
                            <th>{{ __('ltm/contracts.col_status') }}</th>
                            <th>{{ __('ltm/contracts.col_value') }}</th>
                            <th>{{ __('ltm/contracts.col_validity') }}</th>
                            <th class="text-end">{{ __('ltm/common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contracts as $contract)
                            <tr>
                                <td>{{ ($contracts->currentPage() - 1) * $contracts->perPage() + $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $contract->contract_number }}</td>
                                <td>{{ $contract->auction->auction_number ?? '-' }}</td>
                                <td>{{ $contract->client->name ?? '-' }}</td>
                                <td>{{ $contract->carrier->name ?? '-' }}</td>
                                <td>{{ $contract->contract_type }}</td>
                                <td><span class="badge bg-secondary">{{ $contract->status }}</span></td>
                                <td>{{ number_format((float)$contract->total_value_eur, 0, ',', '.') }} {{ $contract->currency->code ?? '' }}</td>
                                <td>{{ $contract->valid_from }} - {{ $contract->valid_to }}</td>
                                <td class="text-end">
                                    <a href="{{ route('ltm.contracte.edit', $contract) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('ltm/common.edit') }}
                                    </a>
                                    <form action="{{ route('ltm.contracte.destroy', $contract) }}" method="POST" class="d-inline">
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
                                <td colspan="10" class="text-center py-4 text-muted">{{ __('ltm/contracts.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $contracts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
