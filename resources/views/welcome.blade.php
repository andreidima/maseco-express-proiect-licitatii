@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5 text-center">
                <h1 class="mb-3">{{ __('pages/welcome.title') }}</h1>
                <p class="text-muted mb-4">{{ __('pages/welcome.lead') }}</p>

                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    @auth
                        <a href="{{ route('acasa') }}" class="btn btn-primary text-white">
                            {{ __('pages/welcome.dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary text-white">
                            {{ __('pages/welcome.login') }}
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-secondary">
                                {{ __('pages/welcome.register') }}
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection

