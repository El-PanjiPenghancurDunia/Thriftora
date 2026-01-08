<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thriftora - Gaya Unik Harga Asik</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #1a1a1a; color: white; }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1523381210434-271e8be1f52b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            height: 85vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            border-bottom: 5px solid #ffc107;
        }
        
        /* Search Bar di Hero */
        .search-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 30px;
        }
        .form-control-search {
            background-color: rgba(0, 0, 0, 0.5);
            border: 1px solid #555;
            color: white;
            padding: 15px;
            border-radius: 10px 0 0 10px;
        }
        .form-control-search:focus {
            background-color: black;
            color: white;
            border-color: #ffc107;
            box-shadow: none;
        }
        .btn-search {
            border-radius: 0 10px 10px 0;
            padding: 0 30px;
            font-weight: bold;
        }

        /* Card Style (Sama persis dengan Home Pembeli) */
        .hover-effect { transition: transform 0.3s, border-color 0.3s; }
        .hover-effect:hover { transform: translateY(-5px); border-color: #ffc107 !important; }
        .hover-warning:hover { background-color: #ffc107; color: black; border-color: #ffc107; }

        /* Navbar & Footer */
        .navbar { background-color: #212529 !important; box-shadow: 0 2px 10px rgba(0,0,0,0.3); }
        footer { background-color: #212529; color: #adb5bd; padding: 50px 0; margin-top: 50px; }
        footer a { color: #adb5bd; text-decoration: none; }
        footer a:hover { color: #ffc107; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-uppercase" href="#">
                <i class="bi bi-bag-heart-fill text-warning"></i> Thriftora
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/home') }}" class="btn btn-outline-warning ms-2">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">Log in</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="btn btn-warning text-dark ms-2 fw-bold">Register</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <h1 class="display-3 fw-bold text-white mb-3" style="text-shadow: 2px 2px 10px black;">
                Thrift <span class="text-warning">Fashion</span> Terbaik
            </h1>
            <p class="fs-5 text-light mb-4" style="text-shadow: 1px 1px 5px black;">
                Temukan barang branded original dengan harga miring.
            </p>
            
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="search-container">
                        <form action="{{ url('/') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control form-control-search" placeholder="Cari baju, celana, jaket..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-warning btn-search">CARI</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5 border-bottom border-secondary pb-3">
                <h2 class="fw-bold text-white">Etalase Produk</h2>
                @if(request('search'))
                    <span class="text-warning">Hasil pencarian: "{{ request('search') }}"</span>
                @else
                    <span class="text-warning">Fresh Drop <i class="bi bi-fire text-danger"></i></span>
                @endif
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                <div class="col-md-3 col-6"> 
                    <div class="card h-100 bg-dark text-white border-secondary shadow-sm hover-effect">
                    <a href="{{ route('product.detail', $product->id) }}" class="text-decoration-none">    
                        <div style="height: 350px; overflow: hidden;" class="position-relative">
                                @if($product->gambar)
                                    <img src="{{ asset('storage/' . $product->gambar) }}" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="{{ $product->nama_produk }}">
                                @else
                                    <img src="https://via.placeholder.com/300x400?text=No+Image" class="card-img-top w-100 h-100" style="object-fit: cover;">
                                @endif
                                <span class="position-absolute top-0 end-0 badge bg-warning text-dark m-2 shadow">{{ $product->ukuran }}</span>
                        </div>
                    </a>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate fw-bold">{{ $product->nama_produk }}</h5>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-warning fw-bold fs-5">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            </div>

                            <p class="card-text small text-secondary mb-3">
                                <i class="bi bi-tag-fill me-1"></i> {{ $product->kondisi }}
                            </p>

                            <div class="mt-auto">
                                @auth
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST"> 
                                        @csrf
                                        <button type="submit" class="btn btn-outline-light w-100 hover-warning">
                                            <i class="bi bi-cart-plus"></i> + Keranjang
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-light w-100 hover-warning">
                                        <i class="bi bi-box-arrow-in-right"></i> Login untuk Beli
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">Tidak ada produk ditemukan.</h4>
                    <a href="{{ url('/') }}" class="btn btn-outline-warning mt-3">Reset Pencarian</a>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row text-center text-md-start">
                <div class="col-md-4 mb-4">
                    <h5 class="text-white fw-bold mb-3"><i class="bi bi-bag-heart-fill text-warning"></i> THRIFTORA</h5>
                    <p class="small">Platform jual beli barang thrift terpercaya. Temukan barang impianmu dengan harga terbaik dan kualitas yang terjaga.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-white fw-bold mb-3">Menu</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Tentang Kami</a></li>
                        <li class="mb-2"><a href="#">Cara Belanja</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-white fw-bold mb-3">Hubungi Kami</h5>
                    <p class="small"><i class="bi bi-geo-alt-fill me-2"></i> Jakarta, Indonesia</p>
                    <p class="small"><i class="bi bi-envelope-fill me-2"></i> support@thriftora.com</p>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="text-center small">
                &copy; {{ date('Y') }} Thriftora. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>