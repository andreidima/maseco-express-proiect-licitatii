@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">{{ __('support.participant.show.title') }}</h3>
        <a class="btn btn-outline-secondary" href="{{ route('participant.support.index') }}">
            <i class="fa-solid fa-arrow-left me-1"></i> {{ __('support.back_to_list') }}
        </a>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold">{{ $supportThread->subject }}</h5>
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
                    <h5 class="card-title">{{ __('support.participant.show.messages_title') }}</h5>
                    <div class="mb-3">
                        @forelse($messages as $message)
                            @include('support._message', ['message' => $message])
                        @empty
                            <p class="text-center text-muted mb-0">{{ __('support.participant.show.no_messages') }}</p>
                        @endforelse
                    </div>
                    <form method="POST" action="{{ route('participant.support.messages.store', $supportThread) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ __('support.participant.show.reply_label') }}</label>
                            <textarea name="body" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-paper-plane me-1"></i> {{ __('support.participant.show.reply_button') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
