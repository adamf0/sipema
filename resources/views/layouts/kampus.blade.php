<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('meta')

    <title>{{ config('app.name', 'SIPEMA') }}</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @stack('css')
</head>

<body>
    <nav class="navbar navbar-expand-lg shadow-sm top-0 sticky-top bg-white">
        <div class="container">
            <button class="btn border" style="margin-right: 16px;" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="offcanvasExample">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand fw-bold" href="#">{{ config('app.name', 'SIPEMA') }}</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mt-2 mt-lg-0 ms-auto mb-2 mb-lg-0">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        @role('User')
                            <li class="nav-item {{ count(Auth::user()->user_kampus) > 0 ? 'dropdown' : '' }}">
                                <a class="nav-link {{ count(Auth::user()->user_kampus) > 0 ? 'dropdown-toggle' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ Session::get('nama_kampus') }}</a>
                                @if (count(Auth::user()->user_kampus) > 0)
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            @foreach (Auth::user()->user_kampus as $kampus)
                                                <a class="dropdown-item" href="{{ route('kampus.switch', ['id_kampus' => $kampus->id_kampus, 'to' => base64_encode(Route::currentRouteName())]) }}">{{ $kampus->kampus->nama_kampus }}</a>
                                            @endforeach
                                        </li>
                                    </ul>
                                @endif
                            </li>
                        @endrole

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">{{ Auth::user()->name }}</a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="dropdown-item-text">
                                    <div>Login Sebagai : </div>
                                    @foreach (Auth::user()->roles as $role)
                                        <div class="fw-semibold">
                                            {{ $role->name }}
                                        </div>
                                    @endforeach
                                </li>
                                <hr class="dropdown-divider">
                                <li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <div class="navbar navbar-expand-lg bg-light">
        <div class="container">
            <div class="navbar-brand fw-semibold text-break text-wrap">
                <h6 class="text-muted mb-0">
                    @yield('nama-kampus')
                </h6>
                <div>
                    @yield('page-title')
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
        <div class="offcanvas-header border-bottom">
            <div>
                <h5 class="offcanvas-title fw-bold" id="sidebarLabel">SIPEMA</h5>
                <h6 class="text-muted">@yield('nama-kampus')</h6>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <ul class="nav nav-pills flex-column mb-auto px-3 pt-3">
                <li class="nav-item">
                    <a href="{{ route('detail-kampus.dashboard', ['kampus' => $kampus->id]) }}" class="nav-link {{ request()->routeIs('detail-kampus.dashboard') ? 'active' : 'link-dark' }}">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('detail-kampus.mou.index', ['kampus' => $kampus->id]) }}" class="nav-link {{ request()->routeIs('detail-kampus.mou.*') ? 'active' : 'link-dark' }}">
                        MOU
                    </a>
                </li>
                <li>
                    <a href="{{ route('detail-kampus.prodi.index', ['kampus' => $kampus->id]) }}" class="nav-link {{ request()->routeIs('detail-kampus.prodi.*') ? 'active' : 'link-dark' }}">
                        Prodi
                    </a>
                </li>
                <li>
                    <a href="{{ route('detail-kampus.gelombang.index', ['kampus' => $kampus->id]) }}" class="nav-link {{ request()->routeIs('detail-kampus.gelombang.*') ? 'active' : 'link-dark' }}">
                        Gelombang
                    </a>
                </li>
                <li>
                    <a href="{{ route('detail-kampus.item-bayar.index', ['kampus' => $kampus->id]) }}" class="nav-link {{ request()->routeIs('detail-kampus.item-bayar.*') ? 'active' : 'link-dark' }}">
                        Rincian Biaya
                    </a>
                </li>
                <li>
                    <a href="{{ route('detail-kampus.mahasiswa.index', ['kampus' => $kampus->id]) }}" class="nav-link {{ request()->routeIs('detail-kampus.mahasiswa.*') ? 'active' : 'link-dark' }}">
                        Mahasiswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('detail-kampus.pembayaran.index', ['kampus' => $kampus->id]) }}" class="nav-link {{ request()->routeIs('detail-kampus.pembayaran.*') ? 'active' : 'link-dark' }}">
                        Metode Pembayaran
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link link-dark">
                        [WIP] Jadwal Ulang Tagihan
                    </a>
                </li>
            </ul>
            <div class="position-relative d-flex align-items-center mb-1">
                <div class="position-absolute bg-white ms-2 px-2 fw-semibold" style="z-index: 10;">Lainnya</div>
                <hr class="w-100" />
            </div>
            <ul class="nav nav-pills flex-column mb-auto px-3">
                <li class="nav-item">
                    <a href="{{ route('master.kampus.index') }}" class="nav-link {{ request()->routeIs('master.kampus.index') ? 'active' : 'link-dark' }}">
                        Kembali ke Master Kampus
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <main class="container py-4">
        @if (session('flash_message'))
            <div class="alert alert-{{ session('flash_message')->type }} alert-dismissible fade show d-flex pe-3" role="alert">
                <div class="flex-grow-1 text-break">
                    <div class="fw-semibold fs-5">
                        {{ session('flash_message')->title }}
                    </div>
                    <div>
                        {{ session('flash_message')->message }}
                    </div>
                </div>
                <button type="button" class="btn btn-outline-{{ session('flash_message')->type }}" data-bs-dismiss="alert" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
                    </svg>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('js')
</body>

</html>
