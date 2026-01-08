<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Thriftora') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 70px;
        }
        .navbar { background-color: #212529 !important; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .nav-link { color: #adb5bd !important; font-weight: 500; transition: color 0.3s; }
        .nav-link:hover, .nav-link.active { color: #ffc107 !important; }
        .btn-warning { color: #212529; font-weight: 600; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        footer { margin-top: auto; background-color: #212529; color: #adb5bd; padding: 40px 0; }
        footer a { color: #adb5bd; text-decoration: none; }
        footer a:hover { color: #ffc107; }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark fixed-top">
            <div class="container">
                <a class="navbar-brand text-uppercase fw-bold" href="{{ url('/') }}">
                    <i class="bi bi-bag-heart-fill text-warning"></i> Thriftora
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('home') ? 'active' : '' }}" href="{{ route('home') }}">Belanja</a>
                            </li>
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center gap-2">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Log in</a></li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item"><a class="btn btn-warning btn-sm px-4 rounded-pill" href="{{ route('register') }}">Register</a></li>
                            @endif
                        @else
                            @if(Auth::user()->role == 'pembeli')
                                <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}"><i class="bi bi-cart-fill fs-5"></i></a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('pembeli.orders') }}">Riwayat</a></li>
                            @endif

                            @if(Auth::user()->role == 'penjual')
                                <li class="nav-item"><a class="btn btn-outline-warning btn-sm" href="{{ route('seller.dashboard') }}"><i class="bi bi-shop"></i> Dashboard Toko</a></li>
                            @endif
                            
                            @if(Auth::user()->role == 'admin')
                                <li class="nav-item"><a class="btn btn-outline-danger btn-sm" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-lock"></i> Admin Panel</a></li>
                            @endif

                            <li class="nav-item dropdown ms-2">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="rounded-circle border border-warning" width="35" height="35" style="object-fit: cover;">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=ffc107&color=000" class="rounded-circle" width="30">
                                    @endif
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person-circle text-warning me-2"></i> Edit Profil
                                    </a>
                                    
                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right text-danger me-2"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <h5 class="text-white fw-bold mb-3"><i class="bi bi-bag-heart-fill text-warning"></i> THRIFTORA</h5>
                        <p class="small">Platform jual beli barang thrift terpercaya.</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="text-white fw-bold">Menu</h6>
                        <ul class="list-unstyled small">
                            <li><a href="#">Tentang Kami</a></li>
                            <li><a href="#">Cara Belanja</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-white fw-bold">Kontak</h6>
                        <p class="small mb-1"><i class="bi bi-envelope-fill me-2"></i> help@thriftora.com</p>
                    </div>
                </div>
                <hr class="border-secondary my-3">
                <div class="text-center small">&copy; {{ date('Y') }} Thriftora. All rights reserved.</div>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>