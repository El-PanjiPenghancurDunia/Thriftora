@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-white"><i class="bi bi-receipt text-warning"></i> Detail Pesanan</h2>
        <a href="{{ route('seller.orders') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card bg-dark text-white border-secondary shadow-lg mb-4">
                <div class="card-header border-secondary bg-secondary bg-opacity-10 py-3">
                    <span class="text-secondary small">ID Transaksi: #{{ $trx->id }}</span>
                    <span class="float-end text-secondary small">{{ $trx->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="alert {{ $trx->status == 'Selesai' ? 'alert-success' : ($trx->status == 'Dikirim' ? 'alert-primary' : 'alert-warning') }} d-flex align-items-center border-0 mb-4">
                        <i class="bi {{ $trx->status == 'Selesai' ? 'bi-check-circle-fill' : 'bi-info-circle-fill' }} fs-4 me-3"></i>
                        <div>
                            <strong class="d-block text-uppercase">Status: {{ $trx->status }}</strong>
                            <small>{{ $trx->status == 'Dibayar' ? 'Segera kirim barang dan input resi.' : 'Pantau terus status paket ini.' }}</small>
                        </div>
                    </div>

                    <h5 class="fw-bold text-warning mb-3">Produk Dipesan</h5>
                    <div class="d-flex align-items-center bg-black bg-opacity-25 p-3 rounded border border-secondary">
                        <img src="{{ asset('storage/' . $trx->product->gambar) }}" width="80" height="80" class="rounded object-fit-cover border border-secondary me-3">
                        <div class="flex-grow-1">
                            <h5 class="mb-1 fw-bold">{{ $trx->product->nama_produk }}</h5>
                            <span class="badge bg-secondary mb-2">{{ $trx->product->ukuran }}</span>
                            <div class="text-secondary small">Kuantitas: <span class="text-white fw-bold">{{ $trx->quantity }} pcs</span></div>
                        </div>
                        <div class="text-end">
                            <small class="text-secondary d-block">Total Harga</small>
                            <span class="fs-5 fw-bold text-warning">Rp {{ number_format($trx->total_harga) }}</span>
                        </div>
                    </div>

                    @if($trx->status == 'Dibayar')
                        <hr class="border-secondary my-4">
                        <h5 class="fw-bold text-white mb-3"><i class="bi bi-box-seam"></i> Proses Pengiriman</h5>
                        <form action="{{ route('seller.orders.ship', $trx->id) }}" method="POST" class="p-3 bg-secondary bg-opacity-10 rounded border border-secondary">
                            @csrf
                            <div class="mb-3">
                                <label class="text-secondary small mb-1">Kurir Dipilih Pembeli</label>
                                <input type="text" class="form-control bg-dark text-white border-secondary" value="{{ strtoupper($trx->courier) }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="text-warning small mb-1 fw-bold">Masukkan Nomor Resi</label>
                                <input type="text" name="nomor_resi" class="form-control bg-dark text-white border-warning" placeholder="Contoh: JP1234567890" required>
                            </div>
                            <button type="submit" class="btn btn-warning fw-bold w-100"><i class="bi bi-send-fill"></i> Kirim Barang</button>
                        </form>
                    @endif
                    
                    @if($trx->status == 'Dikirim' || $trx->status == 'Selesai')
                         <hr class="border-secondary my-4">
                         <div class="p-3 bg-black bg-opacity-25 rounded border border-secondary">
                             <small class="text-secondary d-block">Nomor Resi Pengiriman</small>
                             <span class="fs-5 fw-bold text-white tracking-wider">{{ $trx->nomor_resi }}</span>
                         </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark text-white border-secondary shadow-lg">
                <div class="card-header border-secondary fw-bold text-warning">
                    <i class="bi bi-person-lines-fill"></i> Data Pembeli
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ $trx->user->name }}&background=ffc107&color=000" class="rounded-circle mb-2" width="60">
                        <h5 class="fw-bold">{{ $trx->user->name }}</h5>
                        <span class="badge bg-secondary">{{ $trx->user->email }}</span>
                    </div>

                    <ul class="list-group list-group-flush border-secondary">
                        <li class="list-group-item bg-transparent text-white border-secondary px-0">
                            <small class="text-secondary d-block">Nomor HP / WhatsApp</small>
                            <span class="fw-bold">{{ $trx->user->no_hp }}</span>
                        </li>
                        <li class="list-group-item bg-transparent text-white border-secondary px-0">
                            <small class="text-secondary d-block">Alamat Pengiriman</small>
                            <p class="mb-0 mt-1 p-2 bg-secondary bg-opacity-10 rounded border border-secondary" style="font-family: monospace;">
                                {{ $trx->user->alamat_pengiriman }}
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    body {
        background-color: #1a1a1a;
    }
</style>
@endsection