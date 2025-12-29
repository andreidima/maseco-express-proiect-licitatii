@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/clients.index_title'),
        'subtitle' => __('ltm/clients.index_subtitle'),
        'buttonLabel' => __('ltm/clients.add_button'),
        'buttonRoute' => route('ltm.clienti.create'),
        'badges' => [
            __('ltm/clients.badge_total', ['count' => ($stats['total'] ?? 0)]),
            __('ltm/clients.badge_with_contracts', ['count' => ($stats['withContracts'] ?? 0)]),
            __('ltm/clients.badge_in_auctions', ['count' => ($stats['withAuctions'] ?? 0)]),
        ],
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('ltm.clienti.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/clients.filter_name') }}</label>
                    <input type="text" name="name" value="{{ $filters['name'] ?? '' }}" class="form-control" placeholder="{{ __('ltm/clients.placeholder_name') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/clients.filter_cui') }}</label>
                    <input type="text" name="cui" value="{{ $filters['cui'] ?? '' }}" class="form-control" placeholder="RO12345678">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/clients.filter_registration_number') }}</label>
                    <input type="text" name="registration_number" value="{{ $filters['registration_number'] ?? '' }}" class="form-control" placeholder="J01/000/2025">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/clients.filter_contact_person') }}</label>
                    <input type="text" name="contact_person" value="{{ $filters['contact_person'] ?? '' }}" class="form-control" placeholder="{{ __('ltm/clients.placeholder_contact_person') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/clients.filter_phone') }}</label>
                    <input type="text" name="phone" value="{{ $filters['phone'] ?? '' }}" class="form-control" placeholder="+40...">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/clients.filter_email') }}</label>
                    <input type="text" name="email" value="{{ $filters['email'] ?? '' }}" class="form-control" placeholder="office@client.ro">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/clients.filter_city') }}</label>
                    <input type="text" name="city" value="{{ $filters['city'] ?? '' }}" class="form-control" placeholder="{{ __('ltm/clients.placeholder_city') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/clients.filter_country') }}</label>
                    <select name="country" class="form-select">
                        <option value="">{{ __('ltm/clients.choose_country') }}</option>
                        @foreach($countryOptions as $country)
                            <option value="{{ $country }}" @selected(($filters['country'] ?? '') === $country)>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/clients.filter_payment_terms_min') }}</label>
                    <input type="number" name="payment_terms_min" value="{{ $filters['payment_terms_min'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">{{ __('ltm/clients.filter_payment_terms_max') }}</label>
                    <input type="number" name="payment_terms_max" value="{{ $filters['payment_terms_max'] ?? '' }}" class="form-control" min="0">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary text-white" type="submit">
                        <i class="fa-solid fa-filter me-1"></i> {{ __('ltm/common.filter') }}
                    </button>
                    <a href="{{ route('ltm.clienti.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.reset') }}</a>
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
                            <th>{{ __('ltm/clients.col_name') }}</th>
                            <th>{{ __('ltm/clients.col_cui') }}</th>
                            <th>{{ __('ltm/clients.col_contact_person') }}</th>
                            <th>{{ __('ltm/clients.col_phone') }}</th>
                            <th>{{ __('ltm/clients.col_email') }}</th>
                            <th>{{ __('ltm/clients.col_city') }}</th>
                            <th>{{ __('ltm/clients.col_country') }}</th>
                            <th>{{ __('ltm/clients.col_payment_terms') }}</th>
                            <th class="text-end">{{ __('ltm/common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td>{{ ($clients->currentPage() - 1) * $clients->perPage() + $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $client->name }}</td>
                                <td>{{ $client->cui }}</td>
                                <td>{{ $client->contact_person }}</td>
                                <td>{{ $client->phone }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->city }}</td>
                                <td>{{ $client->country }}</td>
                                <td>{{ $client->payment_terms_days ? $client->payment_terms_days . ' ' . __('ltm/clients.days') : '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('ltm.clienti.edit', $client) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('ltm/common.edit') }}
                                    </a>
                                    <form action="{{ route('ltm.clienti.destroy', $client) }}" method="POST" class="d-inline">
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
                                <td colspan="10" class="text-center py-4 text-muted">{{ __('ltm/clients.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $clients->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
