@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">{{ __('support.admin.index.title') }}</h3>
    </div>

    @include('errors.errors')

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-3 align-items-end" method="GET">
                <div class="col-md-3">
                    <label class="form-label">{{ __('support.admin.index.filter_type') }}</label>
                    <select class="form-select" name="type">
                        <option value="">{{ __('support.admin.index.all_types') }}</option>
                        @foreach($typeOptions as $value => $label)
                            <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('support.admin.index.filter_status') }}</label>
                    <select class="form-select" name="status">
                        <option value="">{{ __('support.admin.index.all_statuses') }}</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-auto">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fa-solid fa-filter me-1"></i> {{ __('support.admin.index.filter_button') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('support.admin.index.participant') }}</th>
                            <th>{{ __('support.admin.index.subject') }}</th>
                            <th>{{ __('support.admin.index.type') }}</th>
                            <th>{{ __('support.admin.index.status') }}</th>
                            <th>{{ __('support.admin.index.last_activity') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($threads as $thread)
                            <tr>
                                <td>{{ $thread->participant?->name ?? __('support.admin.index.deleted_user') }}</td>
                                <td>
                                    <a href="{{ route('support.admin.show', $thread) }}" class="fw-bold">
                                        {{ $thread->subject }}
                                    </a>
                                    <div class="text-muted small">
                                        {{ $thread->latestMessage?->body ? \Illuminate\Support\Str::limit($thread->latestMessage->body, 80) : __('support.admin.index.no_messages') }}
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
                                <td colspan="5" class="text-center text-muted py-4">
                                    {{ __('support.admin.index.empty') }}
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
