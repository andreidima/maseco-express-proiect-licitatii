<!doctype html>
<html class="h-100" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        window.appLocale = @json(app()->getLocale());
        window.__i18n = @json([
            'datepicker' => trans('js.datepicker'),
        ]);
    </script>

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    <!-- Font Awesome links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="d-flex flex-column h-100 @yield('body-class')">
    <header>
        <nav class="navbar navbar-lg navbar-expand-lg navbar-dark shadow culoare1">
            <div class="container">
                <a class="navbar-brand me-3" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @php
                        $ltmActive = request()->routeIs('ltm.*');
                        $participantActive = request()->routeIs('participant.*');
                        $settingsActive = request()->routeIs('settings.*');
                        $reportsActive = request()->routeIs('reports.*');
                        $isParticipant = (Auth::user()?->role) === 'Participant licitatii';
                        $showSupportAdmin = in_array(Auth::user()?->role ?? '', ['SuperAdmin', 'Admin', 'Operator'], true);
                    @endphp
                    @auth
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item me-2">
                                <a class="nav-link active" aria-current="page" href="{{ route('acasa') }}">
                                    <i class="fa-solid fa-house me-1"></i>
                                    {{-- {{ __('ui.home') }} --}}
                                </a>
                            </li>
                            @if ($isParticipant)
                                <li class="nav-item me-2">
                                    <a class="nav-link {{ request()->routeIs('participant.licitatii.*') ? 'active' : '' }}"
                                        href="{{ route('participant.licitatii.index') }}">
                                        <i class="fa-solid fa-gavel me-1"></i> {{ __('ui.auctions') }}
                                    </a>
                                </li>
                                <li class="nav-item me-2">
                                    <a class="nav-link {{ request()->routeIs('participant.oferte.*') ? 'active' : '' }}"
                                        href="{{ route('participant.oferte.index') }}">
                                        <i class="fa-solid fa-tags me-1"></i> {{ __('ui.my_offers') }}
                                    </a>
                                </li>
                                <li class="nav-item me-2">
                                    <a class="nav-link {{ request()->routeIs('participant.support.*') ? 'active' : '' }}"
                                        href="{{ route('participant.support.index') }}">
                                        <i class="fa-solid fa-comments me-1"></i> {{ __('support.nav_participant') }}
                                    </a>
                                </li>
                            @else
                                <li class="nav-item me-2 dropdown">
                                    <a class="nav-link dropdown-toggle {{ $ltmActive ? 'active' : '' }}" href="#" id="ltmDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-truck-fast"></i> {{ __('ui.ltm') }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="ltmDropdown">
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('ltm.dashboard') ? 'active' : '' }}" href="{{ route('ltm.dashboard') }}">
                                                {{ __('ui.ltm_dashboard') }}
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item {{ request()->routeIs('ltm.licitatii.*') ? 'active' : '' }}" href="{{ route('ltm.licitatii.index') }}">{{ __('ui.ltm_auctions') }}</a></li>
                                        <li><a class="dropdown-item {{ request()->routeIs('ltm.loturi.*') ? 'active' : '' }}" href="{{ route('ltm.loturi.index') }}">{{ __('ui.ltm_lots') }}</a></li>
                                        <li><a class="dropdown-item {{ request()->routeIs('ltm.clienti.*') ? 'active' : '' }}" href="{{ route('ltm.clienti.index') }}">{{ __('ui.ltm_clients') }}</a></li>
                                        <li><a class="dropdown-item {{ request()->routeIs('ltm.transportatori.*') ? 'active' : '' }}" href="{{ route('ltm.transportatori.index') }}">{{ __('ui.ltm_carriers') }}</a></li>
                                        <li><a class="dropdown-item {{ request()->routeIs('ltm.curse.*') ? 'active' : '' }}" href="{{ route('ltm.curse.index') }}">{{ __('ui.ltm_routes') }}</a></li>
                                        <li><a class="dropdown-item {{ request()->routeIs('ltm.oferte.*') ? 'active' : '' }}" href="{{ route('ltm.oferte.index') }}">{{ __('ui.ltm_bids') }}</a></li>
                                        <li><a class="dropdown-item {{ request()->routeIs('ltm.contracte.*') ? 'active' : '' }}" href="{{ route('ltm.contracte.index') }}">{{ __('ui.ltm_contracts') }}</a></li>
                                        <li><a class="dropdown-item {{ request()->routeIs('ltm.camioane.*') ? 'active' : '' }}" href="{{ route('ltm.camioane.index') }}">{{ __('ui.ltm_trucks') }}</a></li>
                                        <li><a class="dropdown-item {{ request()->routeIs('ltm.soferi.*') ? 'active' : '' }}" href="{{ route('ltm.soferi.index') }}">{{ __('ui.ltm_drivers') }}</a></li>
                                        <li><a class="dropdown-item {{ request()->routeIs('ltm.documente.*') ? 'active' : '' }}" href="{{ route('ltm.documente.index') }}">{{ __('ui.ltm_documents') }}</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item me-2 dropdown">
                                    <a class="nav-link dropdown-toggle {{ $reportsActive ? 'active' : '' }}" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-chart-pie me-1"></i> {{ __('ui.reports') }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('reports.auctions') ? 'active' : '' }}" href="{{ route('reports.auctions') }}">
                                                <i class="fa-solid fa-gavel me-1"></i> {{ __('ui.reports_auctions') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('reports.bids') ? 'active' : '' }}" href="{{ route('reports.bids') }}">
                                                <i class="fa-solid fa-tags me-1"></i> {{ __('ui.reports_bids') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('reports.contracts') ? 'active' : '' }}" href="{{ route('reports.contracts') }}">
                                                <i class="fa-solid fa-file-contract me-1"></i> {{ __('ui.reports_contracts') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('reports.master_data') ? 'active' : '' }}" href="{{ route('reports.master_data') }}">
                                                <i class="fa-solid fa-layer-group me-1"></i> {{ __('ui.reports_master_data') }}
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('reports.users') ? 'active' : '' }}" href="{{ route('reports.users') }}">
                                                <i class="fa-solid fa-users me-1"></i> {{ __('ui.reports_users') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('reports.support') ? 'active' : '' }}" href="{{ route('reports.support') }}">
                                                <i class="fa-solid fa-headset me-1"></i> {{ __('ui.reports_support') }}
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            <li class="nav-item me-2">
                                <a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}"
                                    href="{{ route('notifications.index') }}">
                                    <i class="fa-solid fa-bell me-1"></i> {{ __('ui.notifications') }}
                                    @if (($unreadNotificationsCount ?? 0) > 0)
                                        <span class="badge bg-danger ms-1">{{ $unreadNotificationsCount }}</span>
                                    @endif
                                </a>
                            </li>
                            @if (!$isParticipant)
                                @if ($showSupportAdmin)
                                    <li class="nav-item me-2">
                                        <a class="nav-link {{ request()->routeIs('support.admin.*') ? 'active' : '' }}"
                                            href="{{ route('support.admin.index') }}">
                                            <i class="fa-solid fa-headset me-1"></i> {{ __('support.nav_admin') }}
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item me-2 dropdown">
                                    <a class="nav-link dropdown-toggle {{ $settingsActive ? 'active' : '' }}" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-gear me-1"></i> {{ __('ui.settings') }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="settingsDropdown">
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('settings.currencies.*') ? 'active' : '' }}" href="{{ route('settings.currencies.index') }}">
                                                <i class="fa-solid fa-coins me-1"></i> {{ __('ui.currencies') }}
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            @can('admin-action')
                                <li class="nav-item me-2">
                                    <a class="nav-link" href="{{ route('users.index') }}">
                                        <i class="fa-solid fa-users me-1"></i> {{ __('ui.users') }}
                                    </a>
                                </li>
                            @endcan
                            @can('super-admin-action')
                                @php
                                    $techActive = request()->routeIs('tech.*');
                                @endphp
                                <li class="nav-item me-2 dropdown">
                                    <a class="nav-link dropdown-toggle {{ $techActive ? 'active' : '' }}" href="#" id="techDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-microchip me-1"></i> {{ __('ui.tech') }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="techDropdown">
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('tech.impersonation.*') ? 'active' : '' }}" href="{{ route('tech.impersonation.index') }}">
                                                <i class="fa-solid fa-user-secret me-1"></i> {{ __('ui.impersonation_users') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('tech.cronjobs.*') ? 'active' : '' }}" href="{{ route('tech.cronjobs.index') }}">
                                                <i class="fa-solid fa-clock-rotate-left me-1"></i> {{ __('ui.cronjob_logs') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('tech.migrations.*') ? 'active' : '' }}" href="{{ route('tech.migrations.index') }}">
                                                <i class="fa-solid fa-database me-1"></i> {{ __('ui.migrations') }}
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endcan
                        </ul>
                    @endauth

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item me-2 d-flex align-items-center">
                            <form method="POST" action="{{ route('locale.update') }}" class="d-flex align-items-center gap-2">
                                @csrf
                                <select name="locale" class="form-select form-select-sm bg-white" onchange="this.form.submit()" aria-label="{{ __('ui.language') }}">
                                    <option value="ro" @selected(app()->getLocale() === 'ro')>RO</option>
                                    <option value="en" @selected(app()->getLocale() === 'en')>EN</option>
                                </select>
                            </form>
                        </li>
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('ui.login') }}</a>
                                </li>
                            @endif

                            {{-- @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif --}}
                        @else
                            <li class="nav-item dropdown me-2">
                                <a class="nav-link dropdown-toggle rounded-3 {{ request()->routeIs('profile.*') ? 'active culoare2' : 'text-white' }}"
                                    href="#" id="navbarAuthentication" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user me-1"></i> {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarAuthentication">
                                    @if ($isParticipant)
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('participant.profil.*') ? 'active' : '' }}"
                                                href="{{ route('participant.profil.edit') }}">
                                                <i class="fa-solid fa-id-card me-1"></i> {{ __('ui.profile') }}
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    @if (session()->has('impersonator_id'))
                                        <li>
                                            <span class="dropdown-item-text text-warning small d-flex align-items-center gap-1">
                                                <i class="fa-solid fa-mask me-1"></i>
                                                {{ __('ui.impersonating_badge', ['name' => session('impersonator_name') ?? __('ui.original_account')]) }}
                                            </span>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fa-solid fa-sign-out-alt me-1"></i> {{ __('ui.logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                    @if (session()->has('impersonator_id'))
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('tech.impersonation.stop') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-warning">
                                                    <i class="fa-solid fa-user-shield me-1"></i>
                                                    {{ __('ui.return_to', ['name' => session('impersonator_name') ?? __('ui.original_account')]) }}
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="flex-shrink-0 py-4 @yield('main-class')">
        @yield('content')
    </main>

    <footer class="mt-auto py-2 text-center text-white culoare1">
        <div class="">
            <p class="mb-1">
                © {{ date('Y') }} {{ config('app.name', 'Laravel') }}
            </p>
            <span class="text-white">
                <a href="https://validsoftware.ro/dezvoltare-aplicatii-web-personalizate/" class="text-white" target="_blank">
                    {{ __('ui.web_app') }}</a>
                {{ __('ui.developed_by') }}
                <a href="https://validsoftware.ro/" class="text-white" target="_blank">
                    validsoftware.ro
                </a>
            </span>
        </div>
    </footer>
</body>
</html>
