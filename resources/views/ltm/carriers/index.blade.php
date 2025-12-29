@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/carriers.index_title'),
        'subtitle' => __('ltm/carriers.index_subtitle'),
        'buttonLabel' => __('ltm/carriers.add_button'),
        'buttonRoute' => route('ltm.transportatori.create'),
        'badges' => [
            __('ltm/carriers.badge_total', ['count' => ($stats['total'] ?? 0)]),
            __('ltm/carriers.badge_with_contracts', ['count' => ($stats['withContracts'] ?? 0)]),
            __('ltm/carriers.badge_with_bids', ['count' => ($stats['withBids'] ?? 0)]),
        ],
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('ltm.transportatori.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/carriers.filter_name') }}</label>
                    <input type="text" name="name" value="{{ $filters['name'] ?? '' }}" class="form-control" placeholder="{{ __('ltm/carriers.placeholder_name') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/carriers.filter_cui') }}</label>
                    <input type="text" name="cui" value="{{ $filters['cui'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/carriers.filter_registration_number') }}</label>
                    <input type="text" name="registration_number" value="{{ $filters['registration_number'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/carriers.filter_contact_person') }}</label>
                    <input type="text" name="contact_person" value="{{ $filters['contact_person'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/carriers.filter_phone') }}</label>
                    <input type="text" name="phone" value="{{ $filters['phone'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/carriers.filter_email') }}</label>
                    <input type="text" name="email" value="{{ $filters['email'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/carriers.filter_city') }}</label>
                    <input type="text" name="city" value="{{ $filters['city'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/carriers.filter_country') }}</label>
                    <select name="country" class="form-select">
                        <option value="">{{ __('ltm/carriers.choose_country') }}</option>
                        @foreach($countryOptions as $country)
                            <option value="{{ $country }}" @selected(($filters['country'] ?? '') === $country)>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/carriers.filter_rating_min') }}</label>
                    <input type="number" step="0.1" name="rating_min" value="{{ $filters['rating_min'] ?? '' }}" class="form-control" min="0" max="5">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/carriers.filter_rating_max') }}</label>
                    <input type="number" step="0.1" name="rating_max" value="{{ $filters['rating_max'] ?? '' }}" class="form-control" min="0" max="5">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary text-white" type="submit">
                        <i class="fa-solid fa-filter me-1"></i> {{ __('ltm/common.filter') }}
                    </button>
                    <a href="{{ route('ltm.transportatori.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.reset') }}</a>
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
                            <th>{{ __('ltm/carriers.col_name') }}</th>
                            <th>{{ __('ltm/carriers.col_cui') }}</th>
                            <th>{{ __('ltm/carriers.col_contact') }}</th>
                            <th>{{ __('ltm/carriers.col_phone') }}</th>
                            <th>{{ __('ltm/carriers.col_email') }}</th>
                            <th>{{ __('ltm/carriers.col_city') }}</th>
                            <th>{{ __('ltm/carriers.col_country') }}</th>
                            <th>{{ __('ltm/carriers.col_rating') }}</th>
                            <th class="text-end">{{ __('ltm/common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($carriers as $carrier)
                            <tr>
                                <td>{{ ($carriers->currentPage() - 1) * $carriers->perPage() + $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $carrier->name }}</td>
                                <td>{{ $carrier->cui }}</td>
                                <td>{{ $carrier->contact_person }}</td>
                                <td>{{ $carrier->phone }}</td>
                                <td>{{ $carrier->email }}</td>
                                <td>{{ $carrier->city }}</td>
                                <td>{{ $carrier->country }}</td>
                                <td>
                                    @if($carrier->rating)
                                        <span class="badge bg-success">{{ number_format($carrier->rating, 1) }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('ltm.transportatori.edit', $carrier) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('ltm/common.edit') }}
                                    </a>
                                    <form action="{{ route('ltm.transportatori.destroy', $carrier) }}" method="POST" class="d-inline">
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
                                <td colspan="10" class="text-center py-4 text-muted">{{ __('ltm/carriers.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $carriers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
