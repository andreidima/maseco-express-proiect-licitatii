@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">{{ __('support.participant.create.title') }}</h3>
        <a class="btn btn-outline-secondary" href="{{ route('participant.support.index') }}">
            <i class="fa-solid fa-arrow-left me-1"></i> {{ __('support.back_to_list') }}
        </a>
    </div>

    @include('errors.errors')

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('participant.support.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ __('support.participant.create.subject') }}</label>
                    <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" maxlength="150" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('support.participant.create.type') }}</label>
                    <select id="supportType" name="type" class="form-select" required>
                        <option value="chat" @selected(old('type') === 'chat')>{{ __('support.type_chat') }}</option>
                        <option value="problem" @selected(old('type') === 'problem')>{{ __('support.type_problem') }}</option>
                    </select>
                </div>
                <div id="problemFields" class="@if(old('type') !== 'problem') d-none @endif">
                    <div class="mb-3">
                        <label class="form-label">{{ __('support.participant.create.problem_category') }}</label>
                        <input type="text" name="problem_category" class="form-control" value="{{ old('problem_category') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('support.participant.create.problem_severity') }}</label>
                        <input type="text" name="problem_severity" class="form-control" value="{{ old('problem_severity') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('support.participant.create.problem_summary') }}</label>
                        <textarea name="problem_summary" class="form-control" rows="3">{{ old('problem_summary') }}</textarea>
                        <small class="form-text text-muted">{{ __('support.participant.create.problem_summary_hint') }}</small>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('support.participant.create.message') }}</label>
                    <textarea name="body" class="form-control" rows="4" required>{{ old('body') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-paper-plane me-1"></i> {{ __('support.participant.create.send') }}
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        const typeSelect = document.getElementById('supportType');
        const problemFields = document.getElementById('problemFields');

        if (!typeSelect || !problemFields) {
            return;
        }

        const toggleFields = () => {
            problemFields.classList.toggle('d-none', typeSelect.value !== 'problem');
        };

        typeSelect.addEventListener('change', toggleFields);
        toggleFields();
    })();
</script>
@endsection
