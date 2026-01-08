@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-white border-0 shadow-lg" style="border-radius: 15px;">
                <div class="card-header bg-transparent border-secondary pt-4">
                    <h4 class="fw-bold text-warning mb-0"><i class="bi bi-speedometer2"></i> Dashboard Penjual</h4>
                </div>

                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="rounded-circle me-3 border border-warning" width="70" height="70" style="object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=ffc107&color=000" class="rounded-circle me-3" width="60">
                        @endif
                        <div>
                            <h3 class="fw-bold mb-0">Selamat Datang, Juragan!</h3>
                            <p class="text-white mb-0">Anda login sebagai: <strong class="text-white">{{ Auth::user()->name }}</strong></p>
                        </div>
                    </div>
                    
                    <hr class="border-secondary">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-warning w-100 py-3 fw-bold text-start d-flex align-items-center">
                                <i class="bi bi-box-seam fs-3 me-3"></i>
                                <div>
                                    <div class="fs-5">Kelola Produk</div>
                                    <small class=" fw-normal">Tambah, Edit, Hapus Barang</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('seller.orders') }}" class="btn btn-outline-info w-100 py-3 fw-bold text-start d-flex align-items-center">
                                <i class="bi bi-receipt fs-3 me-3"></i>
                                <div>
                                    <div class="fs-5">Pesanan Masuk</div>
                                    <small class=" fw-normal">Cek status & Input Resi</small>
                                </div>
                            </a>
                        </div>
                    </div>
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