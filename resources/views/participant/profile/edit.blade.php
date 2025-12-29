@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('participant/profile.title'),
        'subtitle' => __('participant/profile.subtitle'),
        'badges' => [
            __('participant/profile.badge_role', ['role' => e($user->role ?? '-')]),
        ],
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('participant.profil.update') }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label mb-1">{{ __('participant/profile.name') }}<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-1">{{ __('participant/profile.phone') }}</label>
                        <input type="text" name="telefon" class="form-control" value="{{ old('telefon', $user->telefon) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-1">{{ __('participant/profile.email') }}<span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <label class="form-label mb-1">{{ __('participant/profile.new_password') }}</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password">
                        <div class="form-text">{{ __('participant/profile.password_hint') }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-1">{{ __('participant/profile.new_password_confirm') }}</label>
                        <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-primary text-white" type="submit">
                        <i class="fa-solid fa-save me-1"></i> {{ __('participant/profile.save') }}
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ route('acasa') }}">{{ __('participant/profile.back') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
