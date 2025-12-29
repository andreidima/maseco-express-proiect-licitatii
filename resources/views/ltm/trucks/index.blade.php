@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/trucks.title'),
        'subtitle' => __('ltm/trucks.subtitle'),
        'buttonLabel' => __('ltm/trucks.add_button'),
        'buttonRoute' => route('ltm.camioane.create'),
        'badges' => [
            __('ltm/trucks.badge_total', ['count' => $stats['total'] ?? 0]),
            __('ltm/trucks.badge_with_adr', ['count' => $stats['withAdr'] ?? 0]),
            __('ltm/trucks.badge_avg_capacity', ['amount' => $stats['avgCapacity'] ?? 0]),
        ],
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('ltm.camioane.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/trucks.filter_carrier') }}</label>
                    <select name="carrier_id" class="form-select">
                        <option value="">{{ __('ltm/trucks.option_choose_carrier') }}</option>
                        @foreach($carriers as $carrier)
                            <option value="{{ $carrier->id }}" @selected(($filters['carrier_id'] ?? '') == $carrier->id)>{{ $carrier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/trucks.filter_plate') }}</label>
                    <input type="text" name="plate_number" value="{{ $filters['plate_number'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/trucks.filter_truck_type') }}</label>
                    <select name="truck_type" class="form-select">
                        <option value="">{{ __('ltm/trucks.option_select') }}</option>
                        @foreach($truckTypes as $type)
                            <option value="{{ $type }}" @selected(($filters['truck_type'] ?? '') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/trucks.filter_euro_class') }}</label>
                    <select name="euro_class" class="form-select">
                        <option value="">{{ __('ltm/trucks.option_select') }}</option>
                        @foreach($euroClasses as $euro)
                            <option value="{{ $euro }}" @selected(($filters['euro_class'] ?? '') === $euro)>{{ $euro }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/trucks.filter_adr') }}</label>
                    <select name="has_adr" class="form-select">
                        <option value="">{{ __('ltm/trucks.option_any') }}</option>
                        <option value="da" @selected(($filters['has_adr'] ?? '') === 'da')>{{ __('ltm/trucks.yes') }}</option>
                        <option value="nu" @selected(($filters['has_adr'] ?? '') === 'nu')>{{ __('ltm/trucks.no') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/trucks.filter_weight_min') }}</label>
                    <input type="number" step="0.1" name="weight_min" value="{{ $filters['weight_min'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/trucks.filter_weight_max') }}</label>
                    <input type="number" step="0.1" name="weight_max" value="{{ $filters['weight_max'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary text-white" type="submit">
                        <i class="fa-solid fa-filter me-1"></i> {{ __('ltm/common.filter') }}
                    </button>
                    <a href="{{ route('ltm.camioane.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.reset') }}</a>
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
                            <th>{{ __('ltm/trucks.col_plate') }}</th>
                            <th>{{ __('ltm/trucks.col_carrier') }}</th>
                            <th>{{ __('ltm/trucks.col_type') }}</th>
                            <th>{{ __('ltm/trucks.col_capacity') }}</th>
                            <th>{{ __('ltm/trucks.col_euro') }}</th>
                            <th>{{ __('ltm/trucks.col_adr') }}</th>
                            <th class="text-end">{{ __('ltm/common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trucks as $truck)
                            <tr>
                                <td>{{ ($trucks->currentPage() - 1) * $trucks->perPage() + $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $truck->plate_number }}</td>
                                <td>{{ $truck->carrier->name ?? '-' }}</td>
                                <td>{{ $truck->truck_type }}</td>
                                <td>{{ $truck->max_weight_tons }}</td>
                                <td>{{ $truck->euro_class }}</td>
                                <td>{{ $truck->has_adr }}</td>
                                <td class="text-end">
                                    <a href="{{ route('ltm.camioane.edit', $truck) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('ltm/common.edit') }}
                                    </a>
                                    <form action="{{ route('ltm.camioane.destroy', $truck) }}" method="POST" class="d-inline">
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
                                <td colspan="8" class="text-center py-4 text-muted">{{ __('ltm/trucks.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $trucks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
