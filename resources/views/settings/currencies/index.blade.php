@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('settings/currencies.title'),
        'subtitle' => __('settings/currencies.subtitle'),
        'buttonLabel' => __('settings/currencies.add_button'),
        'buttonRoute' => route('settings.currencies.create'),
        'badges' => [
            __('settings/currencies.badge_total', ['count' => method_exists($currencies, 'total') ? $currencies->total() : $currencies->count()]),
        ],
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('settings/currencies.col_code') }}</th>
                            <th>{{ __('settings/currencies.col_name') }}</th>
                            <th class="text-end">{{ __('ltm/common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($currencies as $currency)
                            <tr>
                                <td>{{ method_exists($currencies, 'firstItem') ? (($currencies->firstItem() ?? 1) + $loop->index) : $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $currency->code }}</td>
                                <td>{{ $currency->name }}</td>
                                <td class="text-end">
                                    <a href="{{ route('settings.currencies.edit', $currency) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('ltm/common.edit') }}
                                    </a>
                                    <form action="{{ route('settings.currencies.destroy', $currency) }}" method="POST" class="d-inline">
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
                                <td colspan="4" class="text-center py-4 text-muted">{{ __('settings/currencies.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($currencies, 'links'))
            <div class="card-footer bg-white">
                {{ $currencies->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

