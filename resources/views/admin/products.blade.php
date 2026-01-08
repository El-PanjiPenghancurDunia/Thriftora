@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-white">🛡️ Moderasi Produk</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card bg-dark text-white border-0 shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead class="bg-secondary text-secondary">
                        <tr>
                            <th class="ps-4 py-3">Produk</th>
                            <th>Penjual</th>
                            <th>Harga</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    @if($product->gambar)
                                        <img src="{{ asset('storage/' . $product->gambar) }}" width="50" class="rounded me-2 border border-secondary">
                                    @endif
                                    <div>
                                        <strong class="text-warning">{{ $product->nama_produk }}</strong><br>
                                        <small class="text-secondary">{{ $product->kondisi }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->user->name }}</td>
                            <td>Rp {{ number_format($product->harga) }}</td>
                            <td class="text-end pe-4">
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" 
                                      onsubmit="return confirm('TAKEDOWN PRODUK? Produk ini akan dihapus paksa.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-slash-circle"></i> Takedown</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-5">Belum ada produk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
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