@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-dark text-white border-0 shadow-lg">
                <div class="card-header bg-transparent border-secondary pt-4">
                    <h4 class="fw-bold text-warning mb-0"><i class="bi bi-shield-lock-fill"></i> Admin Panel</h4>
                </div>

                <div class="card-body p-4">
                    <h5 class="mb-4">Statistik Platform</h5>
                    
                    <div class="row g-3 mb-5">
                        <div class="col-md-3">
                            <div class="p-3 rounded border border-secondary" style="background-color: #2b3035;">
                                <h6 class="text-secondary text-uppercase small">Total User</h6>
                                <h3 class="fw-bold text-white mb-0">{{ number_format($totalUser) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 rounded border border-secondary" style="background-color: #2b3035;">
                                <h6 class="text-secondary text-uppercase small">Total Produk</h6>
                                <h3 class="fw-bold text-warning mb-0">{{ number_format($totalProduk) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 rounded border border-secondary" style="background-color: #2b3035;">
                                <h6 class="text-secondary text-uppercase small">Transaksi Sukses</h6>
                                <h3 class="fw-bold text-success mb-0">{{ number_format($totalTransaksi) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 rounded border border-secondary" style="background-color: #2b3035;">
                                <h6 class="text-secondary text-uppercase small">Perputaran Uang</h6>
                                <h4 class="fw-bold text-info mb-0">Rp {{ number_format($totalPendapatan) }}</h4>
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3">Menu Kelola</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-light w-100 py-3 text-start hover-warning">
                                <i class="bi bi-people fs-4 me-2"></i> Kelola User
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('admin.products') }}" class="btn btn-outline-warning w-100 py-3 text-start">
                                <i class="bi bi-box-seam fs-4 me-2"></i> Moderasi Produk
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('admin.transactions') }}" class="btn btn-outline-info w-100 py-3 text-start">
                                <i class="bi bi-receipt fs-4 me-2"></i> Laporan Transaksi
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