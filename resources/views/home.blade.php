@extends('layouts.app')

@section('content')
<div class="container">
    <div class="p-5 mb-5 rounded-3 text-white shadow-lg position-relative overflow-hidden" 
         style="background: linear-gradient(45deg, #1a1a1a, #2c2c2c); border-left: 5px solid #ffc107;">
        <div class="position-relative z-1">
            <h1 class="display-5 fw-bold">Thriftora Collections</h1>
            <p class="col-md-8 fs-5 text-secondary">Temukan gaya unikmu dengan harga terbaik.</p>
            
            <form action="{{ route('home') }}" method="GET" class="mt-4 col-md-6">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-dark text-white border-secondary" 
                           placeholder="Cari produk..." value="{{ request('search') }}" style="padding: 12px;">
                    <button class="btn btn-warning fw-bold px-4" type="submit">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-2">
        <h3 class="fw-bold text-white mb-0">
            @if(request('search'))
                Hasil Pencarian: "{{ request('search') }}"
            @else
                Terbaru Hari Ini
            @endif
        </h3>
        @if(request('search'))
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        @else
            <span class="text-warning">Fresh Drop <i class="bi bi-fire"></i></span>
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
                        <form action="{{ route('cart.add', $product->id) }}" method="POST"> 
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100 hover-warning">
                                <i class="bi bi-cart-plus"></i> + Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h4 class="text-muted">Produk tidak ditemukan.</h4>
            <p class="text-secondary">Coba kata kunci lain atau kembali lagi nanti.</p>
        </div>
        @endforelse
    </div>
</div>

<style>
    body{
        background-color: #1a1a1a;
    }
    /* Efek Hover Card */
    .hover-effect { transition: transform 0.3s, border-color 0.3s; }
    .hover-effect:hover { transform: translateY(-5px); border-color: #ffc107 !important; }
    .hover-warning:hover { background-color: #ffc107; color: black; border-color: #ffc107; }
</style>
@endsection