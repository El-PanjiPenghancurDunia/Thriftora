@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-white">Etalase Toko</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('seller.dashboard') }}" class="btn btn-outline-light">Kembali</a>
            <a href="{{ route('seller.products.create') }}" class="btn btn-warning fw-bold shadow-sm">+ Tambah Produk</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="card bg-dark text-white border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead class="bg-secondary text-uppercase small text-muted">
                        <tr>
                            <th class="ps-4 py-3">Foto</th>
                            <th class="py-3">Nama Produk</th>
                            <th class="py-3">Harga</th>
                            <th class="py-3">Kondisi</th>
                            <th class="py-3 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="ps-4">
                                @if($product->gambar)
                                    <img src="{{ asset('storage/' . $product->gambar) }}" width="60" height="60" class="rounded object-fit-cover border border-secondary">
                                @else
                                    <span class="text-muted small">No Image</span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-warning">{{ $product->nama_produk }}</strong><br>
                                <small class="text-secondary">Ukuran: {{ $product->ukuran }}</small>
                            </td>
                            <td>Rp {{ number_format($product->harga) }}</td>
                            <td>
                                <span class="badge bg-secondary text-dark">{{ $product->kondisi }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('seller.products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST" 
                                          onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam display-4 d-block mb-3"></i>
                                Belum ada barang dagangan.
                            </td>
                        </tr>
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