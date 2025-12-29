@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">{{ __('support.participant.index.title') }}</h3>
        <a class="btn btn-primary" href="{{ route('participant.support.create') }}">
            <i class="fa-solid fa-comments me-1"></i> {{ __('support.participant.index.new_thread') }}
        </a>
    </div>

    @include('errors.errors')

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('support.participant.index.subject') }}</th>
                            <th>{{ __('support.participant.index.type') }}</th>
                            <th>{{ __('support.participant.index.status') }}</th>
                            <th>{{ __('support.participant.index.last_activity') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($threads as $thread)
                            <tr>
                                <td>
                                    <a href="{{ route('participant.support.show', $thread) }}" class="fw-bold">
                                        {{ $thread->subject }}
                                    </a>
                                    <div class="text-muted small">
                                        {{ $thread->latestMessage?->body ? \Illuminate\Support\Str::limit($thread->latestMessage->body, 80) : __('support.participant.index.no_messages') }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $thread->type === 'problem' ? 'bg-warning' : 'bg-info' }}">
                                        {{ __('support.type_' . $thread->type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $thread->status === 'resolved' ? 'bg-success' : ($thread->status === 'pending' ? 'bg-secondary' : 'bg-primary') }}">
                                        {{ __('support.status_' . $thread->status) }}
                                    </span>
                                </td>
                                <td>
                                    {{ optional($thread->last_activity_at ?? $thread->created_at)->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    {{ __('support.participant.index.empty') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $threads->links() }}
    </div>
</div>
@endsection
