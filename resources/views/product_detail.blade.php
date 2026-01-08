@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ url('/') }}" class="btn btn-outline-secondary mb-4"><i class="bi bi-arrow-left"></i> Kembali</a>

    <div class="row">
        <div class="col-md-5 mb-4">
            <div class="card bg-dark border-secondary shadow-lg overflow-hidden">
                @if($product->gambar)
                    <img src="{{ asset('storage/' . $product->gambar) }}" class="w-100" style="height: 500px; object-fit: cover;">
                @else
                    <img src="https://via.placeholder.com/500" class="w-100">
                @endif
            </div>
        </div>

        <div class="col-md-7">
            <div class="card bg-dark text-white border-secondary shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h1 class="fw-bold text-white mb-2">{{ $product->nama_produk }}</h1>
                            <p class="text-secondary"><i class="bi bi-shop"></i> Penjual: <span class="text-warning fw-bold">{{ $product->user->name }}</span></p>
                        </div>
                        <h2 class="text-warning fw-bold">Rp {{ number_format($product->harga) }}</h2>
                    </div>

                    <hr class="border-secondary my-4">

                    <div class="row mb-4">
                        <div class="col-6">
                            <small class="text-secondary text-uppercase">Kondisi</small>
                            <h5 class="fw-bold">{{ $product->kondisi }}</h5>
                        </div>
                        <div class="col-6">
                            <small class="text-secondary text-uppercase">Ukuran</small>
                            <h5 class="fw-bold">{{ $product->ukuran }}</h5>
                        </div>
                    </div>

                    <div class="mb-4">
                        <small class="text-secondary text-uppercase">Deskripsi</small>
                        <p class="fs-5">{{ $product->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                    </div>

                    @auth
                        @if(Auth::user()->role == 'pembeli')
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-warning w-100 py-3 fw-bold fs-5 shadow">
                                    <i class="bi bi-cart-plus-fill"></i> MASUKKAN KERANJANG
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light w-100 py-3">Login untuk Membeli</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <h3 class="fw-bold text-white mb-4"><i class="bi bi-star-half text-warning"></i> Ulasan Pembeli ({{ $product->reviews->count() }})</h3>
            
            <div class="card bg-dark text-white border-secondary shadow-lg">
                <div class="card-body p-4">
                    @forelse($product->reviews as $review)
                        <div class="d-flex mb-4 border-bottom border-secondary pb-3">
                            <img src="https://ui-avatars.com/api/?name={{ $review->user->name }}&background=random" class="rounded-circle me-3" width="50" height="50">
                            
                            <div>
                                <h6 class="fw-bold mb-1">{{ $review->user->name }} <small class="text-secondary fw-normal ms-2">{{ $review->created_at->diffForHumans() }}</small></h6>
                                <div class="text-warning mb-2">
                                    @for($i=0; $i<$review->rating; $i++) <i class="bi bi-star-fill"></i> @endfor
                                    @for($i=$review->rating; $i<5; $i++) <i class="bi bi-star"></i> @endfor
                                </div>
                                <p class="mb-0 text-light">"{{ $review->comment }}"</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-chat-square-dots fs-1 text-secondary mb-3"></i>
                            <p class="text-secondary">Belum ada ulasan untuk produk ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
<style>
        body{
        background-color: #1a1a1a;
    }
</style>
@endsection