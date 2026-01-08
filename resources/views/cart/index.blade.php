@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-white fw-bold">🛒 Keranjang Belanja</h2>
    
    @if(session('success')) <div class="alert alert-success border-0 shadow">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger border-0 shadow">{{ session('error') }}</div> @endif

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card bg-dark text-white border-secondary shadow-lg">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0">
                            <thead class="bg-secondary text-secondary">
                                <tr>
                                    <th class="ps-4 py-3">Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($carts as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center py-2">
                                            <img src="{{ asset('storage/' . $item->product->gambar) }}" width="60" class="rounded border border-secondary me-3">
                                            <div>
                                                <h6 class="mb-0 fw-bold text-warning">{{ $item->product->nama_produk }}</h6>
                                                <small class="text-secondary">Size: {{ $item->product->ukuran }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($item->product->harga) }}</td>
                                    <td>
                                        <span class="badge bg-dark border border-secondary px-3 py-2">
                                            {{ $item->quantity }} Item
                                        </span>
                                    </td>
                                    <td class="text-warning fw-bold">Rp {{ number_format($item->product->harga * $item->quantity) }}</td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center text-secondary py-5">Keranjang kosong. Ayo belanja!</td></tr>
                                @endforelse 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark text-white border-secondary shadow-lg">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-warning">Ringkasan Belanja</h5>
                    <hr class="border-secondary">
                    <div class="d-flex justify-content-between mb-4">
                        <span class="text-secondary">Total Bayar</span>
                        <span class="fw-bold fs-4 text-white">Rp {{ number_format($total) }}</span>
                    </div>
                    <a href="{{ route('checkout.view') }}" class="btn btn-warning w-100 fw-bold py-3 shadow">LANJUT KE PEMBAYARAN</a>
                </div>
            </div>
        </div>
    </div>
</div>
<style> body { background-color: #1a1a1a; } </style>
@endsection