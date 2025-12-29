@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">{{ __('support.admin.show.title') }}</h3>
        <a class="btn btn-outline-secondary" href="{{ route('support.admin.index') }}">
            <i class="fa-solid fa-arrow-left me-1"></i> {{ __('support.admin.show.back') }}
        </a>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold">{{ $supportThread->subject }}</h5>
                    <p class="mb-1">{{ __('support.admin.show.participant') }}: {{ $supportThread->participant?->name ?? __('support.admin.index.deleted_user') }}</p>
                    @if($supportThread->admin)
                        <p class="mb-1">{{ __('support.admin.show.assigned') }}: {{ $supportThread->admin->name }}</p>
                    @endif
                    <div class="d-flex gap-2 flex-wrap mb-2">
                        <span class="badge bg-info">{{ __('support.type_' . $supportThread->type) }}</span>
                        <span class="badge {{ $supportThread->status === 'resolved' ? 'bg-success' : ($supportThread->status === 'pending' ? 'bg-secondary' : 'bg-primary') }}">
                            {{ __('support.status_' . $supportThread->status) }}
                        </span>
                    </div>
                    @if($supportThread->type === 'problem')
                        <p class="mb-1"><strong>{{ __('support.participant.show.category') }}:</strong> {{ $supportThread->problem_category ?? __('support.participant.show.not_provided') }}</p>
                        <p class="mb-1"><strong>{{ __('support.participant.show.severity') }}:</strong> {{ $supportThread->problem_severity ?? __('support.participant.show.not_provided') }}</p>
                        <p class="mb-0"><strong>{{ __('support.participant.show.summary') }}:</strong></p>
                        <p class="text-muted small">{{ $supportThread->problem_summary ?? __('support.participant.show.not_provided') }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ __('support.admin.show.messages_title') }}</h5>
                    <div class="mb-3">
                        @forelse($messages as $message)
                            @include('support._message', ['message' => $message])
                        @empty
                            <p class="text-center text-muted mb-0">{{ __('support.admin.show.no_messages') }}</p>
                        @endforelse
                    </div>
                    <form method="POST" action="{{ route('support.admin.messages.store', $supportThread) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ __('support.admin.show.response') }}</label>
                            <textarea name="body" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('support.admin.show.update_status') }}</label>
                            <select name="status" class="form-select">
                                <option value="">{{ __('support.admin.show.leave_status') }}</option>
                                @foreach(['open', 'pending', 'resolved'] as $status)
                                    <option value="{{ $status }}" @selected($supportThread->status === $status)>{{ __('support.status_' . $status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-paper-plane me-1"></i> {{ __('support.admin.show.send') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
