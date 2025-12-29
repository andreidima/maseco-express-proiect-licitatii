@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Lang;
@endphp

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h1 class="h4 m-0">{{ __('notifications.menu') }}</h1>

            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> {{ __('notifications.actions.back') }}
            </a>
        </div>

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

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <div class="small text-muted">#{{ $notification->id }}</div>
                    <div class="fw-semibold fs-5">{{ $textRo }}</div>
                    <div class="text-muted">{{ $textEn }}</div>
                </div>

                <dl class="row mb-0">
                    <dt class="col-sm-3">{{ __('notifications.labels.created_at') }}</dt>
                    <dd class="col-sm-9">{{ $notification->created_at->format('Y-m-d H:i:s') }}</dd>

                    <dt class="col-sm-3">{{ __('notifications.labels.type') }}</dt>
                    <dd class="col-sm-9"><code>{{ $notification->type }}</code></dd>

                    <dt class="col-sm-3">{{ __('notifications.labels.context') }}</dt>
                    <dd class="col-sm-9">{{ $notification->context ?? '—' }}</dd>

                    @if ($notification->actor)
                        <dt class="col-sm-3">Actor</dt>
                        <dd class="col-sm-9">{{ $notification->actor->name }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
@endsection

