@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/drivers.title'),
        'subtitle' => __('ltm/drivers.subtitle'),
        'buttonLabel' => __('ltm/drivers.add_button'),
        'buttonRoute' => route('ltm.soferi.create'),
        'badges' => [
            __('ltm/drivers.badge_total', ['count' => $stats['total'] ?? 0]),
            __('ltm/drivers.badge_with_adr', ['count' => $stats['withAdr'] ?? 0]),
            __('ltm/drivers.badge_avg_experience', ['count' => $stats['avgExperience'] ?? 0]),
        ],
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('ltm.soferi.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/drivers.filter_name') }}</label>
                    <input type="text" name="name" value="{{ $filters['name'] ?? '' }}" class="form-control" placeholder="Ion Popescu">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/drivers.filter_carrier') }}</label>
                    <select name="carrier_id" class="form-select">
                        <option value="">{{ __('ltm/drivers.option_choose_carrier') }}</option>
                        @foreach($carriers as $carrier)
                            <option value="{{ $carrier->id }}" @selected(($filters['carrier_id'] ?? '') == $carrier->id)>{{ $carrier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/drivers.filter_phone') }}</label>
                    <input type="text" name="phone" value="{{ $filters['phone'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/drivers.filter_email') }}</label>
                    <input type="text" name="email" value="{{ $filters['email'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/drivers.filter_languages') }}</label>
                    <input type="text" name="languages" value="{{ $filters['languages'] ?? '' }}" class="form-control" placeholder="{{ __('ltm/drivers.form_languages_placeholder') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/drivers.filter_adr') }}</label>
                    <select name="has_adr" class="form-select">
                        <option value="">{{ __('ltm/drivers.option_any') }}</option>
                        <option value="da" @selected(($filters['has_adr'] ?? '') === 'da')>{{ __('ltm/drivers.yes') }}</option>
                        <option value="nu" @selected(($filters['has_adr'] ?? '') === 'nu')>{{ __('ltm/drivers.no') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/drivers.filter_experience_min') }}</label>
                    <input type="number" name="experience_min" value="{{ $filters['experience_min'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/drivers.filter_experience_max') }}</label>
                    <input type="number" name="experience_max" value="{{ $filters['experience_max'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary text-white" type="submit">
                        <i class="fa-solid fa-filter me-1"></i> {{ __('ltm/common.filter') }}
                    </button>
                    <a href="{{ route('ltm.soferi.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.reset') }}</a>
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
                            <th>{{ __('ltm/drivers.col_name') }}</th>
                            <th>{{ __('ltm/drivers.col_carrier') }}</th>
                            <th>{{ __('ltm/drivers.col_phone') }}</th>
                            <th>{{ __('ltm/drivers.col_email') }}</th>
                            <th>{{ __('ltm/drivers.col_languages') }}</th>
                            <th>{{ __('ltm/drivers.col_experience') }}</th>
                            <th>{{ __('ltm/drivers.col_adr') }}</th>
                            <th class="text-end">{{ __('ltm/common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drivers as $driver)
                            <tr>
                                <td>{{ ($drivers->currentPage() - 1) * $drivers->perPage() + $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $driver->name }}</td>
                                <td>{{ $driver->carrier->name ?? '-' }}</td>
                                <td>{{ $driver->phone }}</td>
                                <td>{{ $driver->email }}</td>
                                <td>{{ $driver->languages }}</td>
                                <td>{{ $driver->experience_years }}</td>
                                <td>{{ $driver->has_adr }}</td>
                                <td class="text-end">
                                    <a href="{{ route('ltm.soferi.edit', $driver) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('ltm/common.edit') }}
                                    </a>
                                    <form action="{{ route('ltm.soferi.destroy', $driver) }}" method="POST" class="d-inline">
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
                                <td colspan="9" class="text-center py-4 text-muted">{{ __('ltm/drivers.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $drivers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
