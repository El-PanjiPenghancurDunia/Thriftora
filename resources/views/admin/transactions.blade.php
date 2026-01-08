@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-white">📊 Laporan Transaksi</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light">Kembali</a>
    </div>

    <div class="card bg-dark text-white border-0 shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead class="bg-secondary text-secondary">
                        <tr>
                            <th class="ps-4 py-3">Tanggal</th>
                            <th>Pembeli</th>
                            <th>Produk</th>
                            <th>Penjual</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                        <tr>
                            <td class="ps-4 text-secondary">{{ $trx->created_at->format('d M Y') }}</td>
                            <td>{{ $trx->user->name }}</td>
                            <td>
                                @if($trx->product)
                                    {{ $trx->product->nama_produk }}
                                @else
                                    <em class="text-white">Produk Dihapus</em>
                                @endif
                            </td>
                            <td>
                                @if($trx->product && $trx->product->user)
                                    {{ $trx->product->user->name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="fw-bold text-warning">Rp {{ number_format($trx->total_harga) }}</td>
                            <td>
                                @if($trx->status == 'Menunggu Pembayaran')
                                    <span class="badge bg-secondary">Pending</span>
                                @elseif($trx->status == 'Dibayar')
                                    <span class="badge bg-info text-dark">Paid</span>
                                @elseif($trx->status == 'Dikirim')
                                    <span class="badge bg-success">Shipped</span>
                                @elseif($trx->status == 'Selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    {{-- Jaga-jaga jika ada status lain, tampilkan teks aslinya --}}
                                    <span class="badge bg-dark">{{ $trx->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5">Belum ada transaksi.</td></tr>
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