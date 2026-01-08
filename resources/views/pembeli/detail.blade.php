@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-white">Detail Pesanan</h2>
        <a href="{{ route('pembeli.orders') }}" class="btn btn-outline-secondary text-white border-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card bg-dark text-white border-secondary shadow-lg mb-4">
                <div class="card-header bg-transparent border-secondary d-flex justify-content-between align-items-center">
                    <span class="text-secondary">No. Invoice: <span class="text-white fw-bold">INV-{{ $trx->id }}{{ $trx->created_at->format('dmY') }}</span></span>
                    
                    @if($trx->status == 'Menunggu Pembayaran')
                        <span class="badge bg-warning text-dark px-3 py-2">Belum Dibayar</span>
                    @elseif($trx->status == 'Dibayar')
                        <span class="badge bg-info text-dark px-3 py-2">Lunas / Dikemas</span>
                    @elseif($trx->status == 'Dikirim')
                        <span class="badge bg-success px-3 py-2">Sedang Dikirim</span>
                    @endif
                </div>
                <div class="card-body">
                    <h5 class="text-warning fw-bold mb-3"><i class="bi bi-truck"></i> Informasi Pengiriman</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <p class="text-secondary mb-1">Kurir</p>
                            <h6 class="fw-bold">{{ strtoupper($trx->courier) }} - {{ $trx->service }}</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-secondary mb-1">Nomor Resi</p>
                            @if($trx->nomor_resi)
                                <h5 class="fw-bold text-success font-monospace">{{ $trx->nomor_resi }}</h5>
                                <small class="text-muted">Silakan cek di website kurir terkait.</small>
                            @else
                                <span class="text-muted fst-italic">Menunggu input penjual...</span>
                            @endif
                        </div>
                    </div>

                    <hr class="border-secondary">

                    <h5 class="text-warning fw-bold mb-3"><i class="bi bi-box-seam"></i> Detail Produk</h5>
                    <div class="d-flex align-items-center bg-secondary bg-opacity-10 p-3 rounded border border-secondary">
                        @if($trx->product && $trx->product->gambar)
                            <img src="{{ asset('storage/' . $trx->product->gambar) }}" width="80" class="rounded border border-secondary me-3">
                        @else
                            <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" style="width:80px; height:80px;">
                                <i class="bi bi-image text-dark"></i>
                            </div>
                        @endif
                        
                        <div>
                            @if($trx->product)
                                <h5 class="fw-bold mb-1">{{ $trx->product->nama_produk }}</h5>
                                <span class="badge bg-secondary me-2">{{ $trx->product->ukuran }}</span>
                                <span class="badge bg-secondary">{{ $trx->product->kondisi }}</span>
                                <p class="mt-2 mb-0 text-warning fw-bold">Rp {{ number_format($trx->product->harga) }}</p>
                            @else
                                <h5 class="text-danger fst-italic">Produk ini telah dihapus oleh penjual</h5>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark text-white border-secondary shadow-lg">
                <div class="card-body">
                    <h5 class="text-warning fw-bold mb-3">Rincian Pembayaran</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Harga Barang</span>
                        <span>Rp {{ number_format($trx->total_harga - $trx->shipping_cost) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Ongkos Kirim</span>
                        <span>Rp {{ number_format($trx->shipping_cost) }}</span>
                    </div>
                    
                    <hr class="border-secondary">
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-5 fw-bold">Total Bayar</span>
                        <span class="fs-4 fw-bold text-warning">Rp {{ number_format($trx->total_harga) }}</span>
                    </div>

                    @if($trx->status == 'Menunggu Pembayaran')
                        <div class="d-grid mt-4">
                            <button class="btn btn-success fw-bold" onclick="openFakeMidtrans('{{ $trx->snap_token }}', '{{ $trx->total_harga }}')">
                                BAYAR SEKARANG
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card bg-dark text-white border-secondary shadow-lg mt-3">
                <div class="card-body">
                    <h6 class="text-secondary mb-3">Dijual Oleh:</h6>
                    <div class="d-flex align-items-center">
                        <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px;">
                            {{ substr($trx->product->user->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">{{ $trx->product->user->name ?? 'Seller Tidak Dikenal' }}</h6>
                            <small class="text-success"><i class="bi bi-check-circle-fill"></i> Terverifikasi</small>
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