@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/documents.title'),
        'subtitle' => __('ltm/documents.subtitle'),
        'buttonLabel' => __('ltm/documents.add_button'),
        'buttonRoute' => route('ltm.documente.create'),
        'badges' => [
            __('ltm/documents.badge_total', ['count' => $stats['total'] ?? 0]),
            __('ltm/documents.badge_contracts', ['count' => $stats['contracts'] ?? 0]),
            __('ltm/documents.badge_types', ['count' => $stats['types'] ?? 0]),
        ],
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('ltm.documente.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/documents.filter_type') }}</label>
                    <select name="type" class="form-select">
                        <option value="">{{ __('ltm/documents.option_select') }}</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" @selected(($filters['type'] ?? '') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/documents.filter_contract') }}</label>
                    <select name="contract_id" class="form-select">
                        <option value="">{{ __('ltm/documents.option_choose_contract') }}</option>
                        @foreach($contracts as $contract)
                            <option value="{{ $contract->id }}" @selected(($filters['contract_id'] ?? '') == $contract->id)>{{ $contract->contract_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/documents.filter_auction') }}</label>
                    <select name="auction_id" class="form-select">
                        <option value="">{{ __('ltm/documents.option_choose_auction') }}</option>
                        @foreach($auctions as $auction)
                            <option value="{{ $auction->id }}" @selected(($filters['auction_id'] ?? '') == $auction->id)>{{ $auction->auction_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/documents.filter_client') }}</label>
                    <select name="client_id" class="form-select">
                        <option value="">{{ __('ltm/documents.option_choose_client') }}</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected(($filters['client_id'] ?? '') == $client->id)>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/documents.filter_carrier') }}</label>
                    <select name="carrier_id" class="form-select">
                        <option value="">{{ __('ltm/documents.option_choose_carrier') }}</option>
                        @foreach($carriers as $carrier)
                            <option value="{{ $carrier->id }}" @selected(($filters['carrier_id'] ?? '') == $carrier->id)>{{ $carrier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/documents.filter_path') }}</label>
                    <input type="text" name="file_path" value="{{ $filters['file_path'] ?? '' }}" class="form-control" placeholder="{{ __('ltm/documents.filter_path_placeholder') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/documents.filter_description') }}</label>
                    <input type="text" name="description" value="{{ $filters['description'] ?? '' }}" class="form-control" placeholder="{{ __('ltm/documents.filter_description_placeholder') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary text-white" type="submit">
                        <i class="fa-solid fa-filter me-1"></i> {{ __('ltm/common.filter') }}
                    </button>
                    <a href="{{ route('ltm.documente.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.reset') }}</a>
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
                            <th>{{ __('ltm/documents.col_type') }}</th>
                            <th>{{ __('ltm/documents.col_contract') }}</th>
                            <th>{{ __('ltm/documents.col_auction') }}</th>
                            <th>{{ __('ltm/documents.col_client') }}</th>
                            <th>{{ __('ltm/documents.col_carrier') }}</th>
                            <th>{{ __('ltm/documents.col_file') }}</th>
                            <th>{{ __('ltm/documents.col_description') }}</th>
                            <th class="text-end">{{ __('ltm/common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $document)
                            <tr>
                                <td>{{ ($documents->currentPage() - 1) * $documents->perPage() + $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $document->type }}</td>
                                <td>{{ $document->contract->contract_number ?? '-' }}</td>
                                <td>{{ $document->auction->auction_number ?? '-' }}</td>
                                <td>{{ $document->client->name ?? '-' }}</td>
                                <td>{{ $document->carrier->name ?? '-' }}</td>
                                <td>{{ $document->file_path }}</td>
                                <td>{{ $document->description }}</td>
                                <td class="text-end">
                                    <a href="{{ route('ltm.documente.edit', $document) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('ltm/common.edit') }}
                                    </a>
                                    <form action="{{ route('ltm.documente.destroy', $document) }}" method="POST" class="d-inline">
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
                                <td colspan="9" class="text-center py-4 text-muted">{{ __('ltm/documents.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $documents->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
