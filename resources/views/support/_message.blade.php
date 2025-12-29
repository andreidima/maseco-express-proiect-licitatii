@props(['message'])

@php
    $isAdmin = $message->sender_role === 'admin';
    $senderName = $message->sender?->name ?? ($isAdmin ? __('support.admin_label') : __('support.participant_label'));
@endphp

<div class="card mb-3 @if($isAdmin) border-primary bg-light @else border-secondary @endif">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <strong class="d-block">{{ $senderName }}</strong>
                <small class="text-muted">
                    {{ $isAdmin ? __('support.admin_received') : __('support.participant_sent') }}
                </small>
            </div>
            <small class="text-muted">{{ $message->created_at->format('d.m.Y H:i') }}</small>
        </div>
        <p class="mb-0">{!! nl2br(e($message->body)) !!}</p>
    </div>
</div>
