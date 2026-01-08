@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-white border-0 shadow-lg">
                <div class="card-header bg-transparent border-secondary py-3">
                    <h5 class="mb-0 text-warning fw-bold">Edit Produk</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label text-secondary">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control text-white" value="{{ old('nama_produk', $product->nama_produk) }}" required style="background-color: #2b3035; border: 1px solid #495057;">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary">Harga (Rp)</label>
                                <input type="number" name="harga" class="form-control text-white" value="{{ old('harga', $product->harga) }}" required style="background-color: #2b3035; border: 1px solid #495057;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary">Ukuran</label>
                                <select name="ukuran" class="form-select text-white" style="background-color: #2b3035; border: 1px solid #495057;">
                                    <option {{ $product->ukuran == 'S' ? 'selected' : '' }}>S</option> 
                                    <option {{ $product->ukuran == 'M' ? 'selected' : '' }}>M</option> 
                                    <option {{ $product->ukuran == 'L' ? 'selected' : '' }}>L</option> 
                                    <option {{ $product->ukuran == 'XL' ? 'selected' : '' }}>XL</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary">Kondisi Barang</label>
                            <select name="kondisi" class="form-select text-white" style="background-color: #2b3035; border: 1px solid #495057;">
                                <option value="Like New" {{ $product->kondisi == 'Like New' ? 'selected' : '' }}>Like New</option>
                                <option value="Good Condition" {{ $product->kondisi == 'Good Condition' ? 'selected' : '' }}>Good Condition</option>
                                <option value="Defect Minor" {{ $product->kondisi == 'Defect Minor' ? 'selected' : '' }}>Defect Minor</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control text-white" rows="3" style="background-color: #2b3035; border: 1px solid #495057;">{{ old('deskripsi', $product->deskripsi) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary">Ganti Foto (Opsional)</label>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <img src="{{ asset('storage/' . $product->gambar) }}" width="60" class="rounded border border-secondary">
                                <input type="file" name="gambar" class="form-control text-white" style="background-color: #2b3035; border: 1px solid #495057;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                            <button type="submit" class="btn btn-warning fw-bold px-4">Simpan Perubahan</button>
                        </div>
                    </form>
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