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
                            <li class="nav-item {{ (count(Auth::user()->user_kampus)>0? 'dropdown':'') }}">
                                <a class="nav-link {{ (count(Auth::user()->user_kampus)>0? 'dropdown-toggle':'') }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ Session::get('nama_kampus') }}</a>
                                @if (count(Auth::user()->user_kampus)>0)
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            @foreach (Auth::user()->user_kampus as $kampus)
                                                <a class="dropdown-item" href="{{ route('kampus.switch', ['id_kampus' => $kampus->id_kampus, 'to' => base64_encode(Route::currentRouteName()) ]) }}">{{ $kampus->kampus->nama_kampus }}</a>
                                            @endforeach
                                        </li>
                                    </ul>
                                @endif
                            </li>
                        @endrole
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ Auth::user()->name }}</a>
                            <ul class="dropdown-menu dropdown-menu-end">
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
            <div class="navbar-brand fw-semibold text-break">
                @yield('page-title')
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold" id="sidebarLabel">SIPEMA</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <!-- <div class="position-relative d-flex align-items-center mb-1">
                <div class="position-absolute bg-white ms-2 px-2 fw-semibold" style="z-index: 10;">Untuk Kampus</div>
                <hr class="w-100" />
            </div> -->
            <!-- <ul class="nav nav-pills flex-column mb-auto px-3">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : 'link-dark' }}">
                        Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('kampus.mou.index') }}" class="nav-link {{ request()->routeIs('kampus.mou.*') ? 'active' : 'link-dark' }}">
                        Kampus MOU
                    </a>
                </li>
                <li>
                    <a href="{{ route('kampus.prodi.index') }}" class="nav-link {{ request()->routeIs('kampus.prodi.*') ? 'active' : 'link-dark' }}">
                        Kampus Prodi
                    </a>
                </li>
                <li>
                    <a href="{{ route('kampus.gelombang.index') }}" class="nav-link {{ request()->routeIs('kampus.gelombang.*') ? 'active' : 'link-dark' }}">
                        Kampus Gelombang
                    </a>
                </li>
                <li>
                    <a href="{{ route('kampus.item-bayar.index') }}" class="nav-link {{ request()->routeIs('kampus.item-bayar.*') ? 'active' : 'link-dark' }}">
                        Kampus Item Bayar
                    </a>
                </li>
                <li>
                    <a href="{{ route('kampus.mahasiswa.index') }}" class="nav-link {{ request()->routeIs('kampus.mahasiswa.*') ? 'active' : 'link-dark' }}">
                        Kampus Mahasiswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('kampus.pembayaran.index') }}" class="nav-link {{ request()->routeIs('kampus.pembayaran.*') ? 'active' : 'link-dark' }}">
                        Kampus Pembayaran
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link link-dark">
                        Jadwal Ulang Tagihan
                    </a>
                </li>
            </ul> -->

            <!-- <div class="position-relative d-flex align-items-center mt-4 mb-1">
                <div class="position-absolute bg-white ms-2 px-2 fw-semibold" style="z-index: 10;">Untuk Edunitas</div>
                <hr class="w-100" />
            </div> -->

            @role('Admin')
                <ul class="nav nav-pills flex-column mb-auto px-3">
                    <li class="nav-item">
                        <a href="#" class="nav-link link-dark }}">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('biaya-potongan.index') }}" class="nav-link {{ request()->routeIs('biaya-potongan.*') ? 'active' : 'link-dark' }}">
                            Biaya Potongan
                        </a>
                    </li>
                </ul>

                <div class="position-relative d-flex align-items-center mt-4 mb-1">
                    <div class="position-absolute bg-white ms-2 px-2 fw-semibold" style="z-index: 10;">Master Data</div>
                    <hr class="w-100" />
                </div>
                <ul class="nav nav-pills flex-column mb-auto px-3">
                    <li class="nav-item">
                        <a href="{{ route('master.item.index') }}" class="nav-link {{ request()->routeIs('master.item.*') ? 'active' : 'link-dark' }}">
                            Master Item
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.kelompok.index') }}" class="nav-link {{ request()->routeIs('master.kelompok.*') ? 'active' : 'link-dark' }}">
                            Master Kelompok
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.kampus.index') }}" class="nav-link {{ request()->routeIs('master.kampus.*') ? 'active' : 'link-dark' }}">
                            Master Kampus
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.user.index') }}" class="nav-link {{ request()->routeIs('master.user.*') ? 'active' : 'link-dark' }}">
                            Master User
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.channel-pembayaran.index') }}" class="nav-link {{ request()->routeIs('master.channel-pembayaran.*') ? 'active' : 'link-dark' }}">
                            Master Channel
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.tipe-biaya-potongan.index') }}" class="nav-link {{ request()->routeIs('master.tipe-biaya-potongan.*') ? 'active' : 'link-dark' }}">
                            Master Tipe Biaya Potongan
                        </a>
                    </li>
                </ul>
            @else
                <ul class="nav nav-pills flex-column mb-auto px-3">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : 'link-dark' }}">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kampus.mou.index') }}" class="nav-link {{ request()->routeIs('kampus.mou.*') ? 'active' : 'link-dark' }}">
                            MOU
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kampus.prodi.index') }}" class="nav-link {{ request()->routeIs('kampus.prodi.*') ? 'active' : 'link-dark' }}">
                            Prodi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kampus.gelombang.index') }}" class="nav-link {{ request()->routeIs('kampus.gelombang.*') ? 'active' : 'link-dark' }}">
                            Gelombang
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kampus.item-bayar.index') }}" class="nav-link {{ request()->routeIs('kampus.item-bayar.*') ? 'active' : 'link-dark' }}">
                            Item Bayar
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kampus.mahasiswa.index') }}" class="nav-link {{ request()->routeIs('kampus.mahasiswa.*') ? 'active' : 'link-dark' }}">
                            Mahasiswa
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kampus.pembayaran.index') }}" class="nav-link {{ request()->routeIs('kampus.pembayaran.*') ? 'active' : 'link-dark' }}">
                            Metode Pembayaran
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link link-dark">
                            Jadwal Ulang Tagihan
                        </a>
                    </li>
                </ul>
            @endrole
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
