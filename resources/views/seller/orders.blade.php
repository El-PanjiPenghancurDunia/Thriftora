@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-white">📦 Pesanan Masuk</h2>
        <a href="{{ route('seller.dashboard') }}" class="btn btn-outline-light">Kembali ke Dashboard</a>
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
                            <th class="ps-4 py-3">Tgl Order</th>
                            <th class="py-3">Produk</th>
                            <th class="py-3">Pembeli</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 text-secondary">{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                <strong class="text-warning">{{ $order->product->nama_produk }}</strong><br>
                                <small class="text-muted">Rp {{ number_format($order->product->harga) }}</small>
                            </td>
                            <td>
                                {{ $order->user->name }}<br>
                                <span class="badge bg-secondary text-dark">{{ strtoupper($order->courier) }}</span>
                            </td>
                            <td>
                                @if($order->status == 'Dibayar')
                                    <span class="badge bg-info text-dark">Siap Dikirim</span>
                                @elseif($order->status == 'Dikirim')
                                    <span class="badge bg-success">Sedang Dikirim</span><br>
                                    <small class="text-muted text-break">{{ $order->nomor_resi }}</small>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $order->status }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($order->status == 'Dibayar')
                                    <button type="button" class="btn btn-warning btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#shipModal{{ $order->id }}">
                                        <i class="bi bi-truck"></i> Input Resi
                                    </button>
                                    
                                    @else
                                    <a href="{{ route('seller.orders.show', $order->id) }}" class="btn btn-outline-secondary btn-sm">
                                        Detail
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                Belum ada pesanan masuk.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalResi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary">
            <form id="formResi" method="POST">
                @csrf
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold text-warning">Input Nomor Resi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary">Nomor Resi</label>
                        <input type="text" name="nomor_resi" class="form-control text-white" required placeholder="Contoh: JP123456789" style="background-color: #2b3035; border: 1px solid #495057;">
                        <small class="text-muted">Pastikan barang sudah diserahkan ke kurir.</small>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">Kirim Barang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function inputResi(id) {
        const form = document.getElementById('formResi');
        form.action = "/seller/orders/" + id + "/ship";
        var myModal = new bootstrap.Modal(document.getElementById('modalResi'));
        myModal.show();
    }
</script>
<style>
    body{
        background-color: #1a1a1a;
    }
</style>
@endsection