@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Lang;
@endphp

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <h1 class="h4 m-0">
                {{ $isParticipant ? __('notifications.title_participant') : __('notifications.title_admin') }}
            </h1>

            <form method="POST" action="{{ route('notifications.read_all') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="fa-solid fa-envelope-open-text me-1"></i>
                    {{ __('notifications.actions.mark_all_read') }}
                </button>
            </form>
        </div>

        <form method="GET" action="{{ route('notifications.index') }}" class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label">{{ __('notifications.filters.search') }}</label>
                        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" />
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label">{{ __('notifications.filters.type') }}</label>
                        <select name="type" class="form-select">
                            <option value="">—</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}" @selected(($filters['type'] ?? '') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label">{{ __('notifications.filters.auction') }}</label>
                        <select name="auction_id" class="form-select">
                            <option value="">—</option>
                            @foreach ($auctions as $auction)
                                <option value="{{ $auction->id }}" @selected((string) ($filters['auction_id'] ?? '') === (string) $auction->id)>
                                    {{ $auction->auction_number ?? $auction->title ?? ('#' . $auction->id) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-1">
                        <label class="form-label">{{ __('notifications.filters.from') }}</label>
                        <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control" />
                    </div>

                    <div class="col-6 col-md-1">
                        <label class="form-label">{{ __('notifications.filters.to') }}</label>
                        <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control" />
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="unread" value="1" id="unreadOnly"
                                @checked(($filters['unread'] ?? null) == 1) />
                            <label class="form-check-label" for="unreadOnly">
                                {{ __('notifications.filters.unread_only') }}
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-md-9 text-end">
                        <button type="submit" class="btn btn-primary">
                            {{ __('notifications.filters.apply') }}
                        </button>
                        <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary">
                            {{ __('notifications.filters.reset') }}
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle m-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 110px;">{{ __('notifications.labels.created_at') }}</th>
                            <th style="width: 90px;">{{ __('notifications.labels.type') }}</th>
                            <th>{{ __('notifications.labels.context') }}</th>
                            <th style="width: 120px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($notifications as $notification)
                            @php
                                $data = $notification->data ?? [];
                                $auctionLabel = trim(($data['auction_number'] ?? '') . ' ' . ($data['auction_title'] ?? ''));
                                $replace = [
                                    'auction' => $auctionLabel ?: '-',
                                    'lot' => $data['lot_code'] ?? '-',
                                    'carrier' => $data['carrier_name'] ?? '-',
                                    'type' => $data['type'] ?? '-',
                                    'from' => $data['from'] ?? '-',
                                    'to' => $data['to'] ?? '-',
                                ];
                                $key = 'notifications.types.' . $notification->type;
                                $textRo = Lang::get($key, $replace, 'ro');
                                $textEn = Lang::get($key, $replace, 'en');
                            @endphp

                            <tr class="{{ $notification->is_read ? '' : 'table-warning' }}">
                                <td class="text-nowrap">
                                    <div class="small text-muted">{{ $notification->created_at->format('Y-m-d') }}</div>
                                    <div class="small">{{ $notification->created_at->format('H:i') }}</div>
                                </td>
                                <td class="text-nowrap">
                                    <span class="badge {{ $notification->is_read ? 'bg-secondary' : 'bg-primary' }}">
                                        {{ $notification->is_read ? __('notifications.labels.read') : __('notifications.labels.unread') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $textRo }}</div>
                                    <div class="text-muted small">{{ $textEn }}</div>
                                    @if (!empty($notification->context))
                                        <div class="small mt-1">
                                            <span class="text-muted">#{{ $notification->id }}</span>
                                            <span class="text-muted">·</span>
                                            <span>{{ $notification->context }}</span>
                                        </div>
                                    @endif
                                    @if ($notification->actor)
                                        <div class="small text-muted mt-1">
                                            <i class="fa-solid fa-user me-1"></i> {{ $notification->actor->name }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('notifications.show', $notification) }}">
                                        {{ __('notifications.actions.view') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    {{ __('notifications.empty') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($notifications->hasPages())
                <div class="card-footer">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

