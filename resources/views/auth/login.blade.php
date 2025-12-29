@extends('layouts.app')

@section('body-class', 'login-body')
@section('main-class', 'login-main')

@section('content')
<div class="login-page position-relative overflow-hidden">
    <div class="login-aurora aurora-1"></div>
    <div class="login-aurora aurora-2"></div>
    <div class="login-aurora aurora-3"></div>
    <div class="login-grid"></div>

    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 text-white">
                <div class="d-inline-flex align-items-center gap-2 login-badge mb-3">
                    <span class="badge-dot"></span>
                    <span class="fw-semibold text-uppercase small">{{ __('pages/login.badge') }}</span>
                </div>
                <h1 class="display-5 fw-bold mb-3">
                    {{ __('pages/login.headline') }}
                </h1>
                <p class="lead text-white-50 mb-4">
                    {{ __('pages/login.lead') }}
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <div class="login-metric">
                        <span class="label">{{ __('pages/login.metric_1_label') }}</span>
                        <span class="value">{{ __('pages/login.metric_1_value') }}</span>
                    </div>
                    <div class="login-metric">
                        <span class="label">{{ __('pages/login.metric_2_label') }}</span>
                        <span class="value">{{ __('pages/login.metric_2_value') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-xl-4 ms-lg-auto">
                <div class="glass-card shadow-lg border-0">
                    <div class="d-flex align-items-center mb-4">
                        <div class="login-logo fw-bold">ME</div>
                        <div class="ms-3">
                            <p class="text-uppercase text-white-50 small mb-1">{{ __('pages/login.your_account') }}</p>
                            <h2 class="h5 text-white mb-0">{{ __('pages/login.enter_app') }}</h2>
                        </div>
                    </div>

                    @include ('errors.errors')

                    <form method="POST" action="{{ route('login') }}" class="login-form">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label text-white-50 mb-1">
                                {{ __('auth.E-Mail Address') }}
                            </label>
                            <div class="input-group login-input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input
                                    id="email"
                                    type="email"
                                    class="form-control login-input @error('email') is-invalid @enderror"
                                    name="email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    autofocus
                                    placeholder="{{ __('auth.E-Mail Address') }}"
                                >
                            </div>
                            @error('email')
                                <span class="text-danger small" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label text-white-50 mb-1">
                                {{ __('auth.Password') }}
                            </label>
                            <div class="input-group login-input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input
                                    id="password"
                                    type="password"
                                    class="form-control login-input @error('password') is-invalid @enderror"
                                    name="password"
                                    autocomplete="current-password"
                                    placeholder="{{ __('auth.Password') }}"
                                >
                            </div>
                            @error('password')
                                <span class="text-danger small" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div class="form-check text-white-50">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('auth.Remember Me') }}
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="link-light link-opacity-75-hover text-decoration-none" href="{{ route('password.request') }}">
                                    {{ __('auth.Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn w-100 login-btn">
                            <span>{{ __('auth.Login') }}</span>
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>

                        @if (Route::has('register'))
                            <p class="text-center text-white-50 mt-3 mb-0">
                                {{ __('pages/login.no_account') }}
                                <a class="link-light fw-semibold text-decoration-none" href="{{ route('register') }}">{{ __('pages/login.register') }}</a>
                            </p>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
