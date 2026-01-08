@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-white fw-bold ">Keranjang Belanja</h2>
    
    @if(session('success'))
        <div class="alert alert-success border-0">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0">{{ session('error') }}</div>
    @endif

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card bg-dark text-white border-secondary shadow-lg mb-4">
                <div class="card-body p-0">
                    <table class="table table-dark table-hover align-middle mb-0">
                        <thead class="bg-secondary text-secondary">
                            <tr>
                                <th class="ps-4 py-3">Produk</th>
                                <th class="py-3">Harga</th>
                                <th class="py-3 text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($carts as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $item->product->gambar) }}" width="70" class="me-3 rounded border border-secondary">
                                        <div>
                                            <h6 class="mb-0 fw-bold text-warning">{{ $item->product->nama_produk }}</h6>
                                            <small class="text-secondary">Ukuran: {{ $item->product->ukuran }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($item->product->harga) }}</td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-secondary py-5 ">Keranjang masih kosong. Ayo belanja!</td>
                            </tr>
                            @endforelse 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark text-white border-secondary shadow-lg">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-warning">Ringkasan Belanja</h5>
                    <hr class="border-secondary">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Total Harga</span>
                        <span class="fw-bold fs-5">Rp {{ number_format($total) }}</span>
                    </div>
                    
                    <a href="{{ route('checkout.view') }}" class="btn btn-warning w-100 fw-bold py-2">
                        LANJUT KE PEMBAYARAN
                    </a>
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