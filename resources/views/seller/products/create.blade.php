@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-white border-0 shadow-lg">
                <div class="card-header bg-transparent border-secondary py-3">
                    <h5 class="mb-0 text-warning fw-bold">Jual Barang Thrift Baru</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label text-secondary">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control text-white" required style="background-color: #2b3035; border: 1px solid #495057;">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary">Harga (Rp)</label>
                                <input type="number" name="harga" class="form-control text-white" required style="background-color: #2b3035; border: 1px solid #495057;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary">Ukuran</label>
                                <select name="ukuran" class="form-select text-white" style="background-color: #2b3035; border: 1px solid #495057;">
                                    <option>S</option> <option>M</option> <option>L</option> <option>XL</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary">Kondisi Barang</label>
                            <select name="kondisi" class="form-select text-white" style="background-color: #2b3035; border: 1px solid #495057;">
                                <option value="Like New">Like New (Seperti Baru)</option>
                                <option value="Good Condition">Good Condition (Bagus)</option>
                                <option value="Defect Minor">Defect Minor (Ada cacat dikit)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary">Deskripsi & Minus (Jika ada)</label>
                            <textarea name="deskripsi" class="form-control text-white" rows="3" style="background-color: #2b3035; border: 1px solid #495057;"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary">Foto Produk</label>
                            <input type="file" name="gambar" class="form-control text-white" style="background-color: #2b3035; border: 1px solid #495057;">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                            <button type="submit" class="btn btn-warning fw-bold px-4">JUAL SEKARANG</button>
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