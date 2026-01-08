@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-white"><i class="bi bi-speedometer2 text-warning"></i> Dashboard Penjualan</h2>
        <a href="{{ route('seller.dashboard') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
    </div>

    <div class="card bg-dark border-secondary shadow-lg mb-4">
        <div class="card-body p-3">
            <form action="{{ route('seller.orders') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="text-secondary small mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control bg-black text-white border-secondary" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="text-secondary small mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control bg-black text-white border-secondary" value="{{ $endDate }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-warning fw-bold w-100"><i class="bi bi-funnel"></i> Filter Data</button>
                    <a href="{{ route('seller.orders') }}" class="btn btn-outline-secondary w-25" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body">
                    <small class="text-secondary text-uppercase">Total Pendapatan</small>
                    <h3 class="fw-bold text-warning mt-1">Rp {{ number_format($totalPendapatan) }}</h3>
                    <small class="text-white"><i class="bi bi-calendar-check"></i> Periode Terpilih</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body">
                    <small class="text-secondary text-uppercase">Total Pesanan</small>
                    <h3 class="fw-bold text-white mt-1">{{ $totalPesanan }}</h3>
                    <small class="text-secondary">Transaksi Masuk</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body">
                    <small class="text-secondary text-uppercase">Pesanan Selesai</small>
                    <h3 class="fw-bold text-success mt-1">{{ $pesananSukses }}</h3>
                    <small class="text-secondary">Transaksi Berhasil</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body">
                    <small class="text-secondary text-uppercase">Menunggu Bayar</small>
                    <h3 class="fw-bold text-danger mt-1">{{ $pesananPending }}</h3>
                    <small class="text-secondary">Butuh Follow up</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-dark border-secondary shadow-lg mb-5">
        <div class="card-header border-secondary bg-secondary bg-opacity-10 fw-bold text-white">
            <i class="bi bi-graph-up-arrow text-warning me-2"></i> Grafik Pendapatan Harian
        </div>
        <div class="card-body">
            <canvas id="salesChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <h4 class="fw-bold text-white mb-3"><i class="bi bi-list-ul"></i> Riwayat Pesanan</h4>
    <div class="card bg-dark border-secondary shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead class="bg-secondary text-secondary">
                        <tr>
                            <th class="ps-4 py-3">Tgl Order</th>
                            <th>Produk</th>
                            <th>Pembeli</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-4">{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                <strong class="text-warning">{{ $order->product->nama_produk }}</strong>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $order->user->name }}</div>
                                <span class="badge bg-secondary">{{ strtoupper($order->courier ?? 'Kurir') }}</span>
                            </td>
                            <td>
                                @if($order->status == 'Menunggu Pembayaran')
                                    <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                                @elseif($order->status == 'Dibayar')
                                    <span class="badge bg-info text-dark">Siap Dikirim</span>
                                @elseif($order->status == 'Dikirim')
                                    <span class="badge bg-primary">Sedang Dikirim</span>
                                @elseif($order->status == 'Selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-secondary">{{ $order->status }}</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if($order->status == 'Dibayar')
                                    <a href="{{ route('seller.orders.show', $order->id) }}" class="btn btn-warning btn-sm fw-bold">
                                        <i class="bi bi-box-seam"></i> Proses
                                    </a>
                                @else
                                    <a href="{{ route('seller.orders.show', $order->id) }}" class="btn btn-outline-secondary btn-sm">Detail</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                Tidak ada data pesanan pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Setup Data dari Laravel Controller
    const labels = @json($chartLabels);
    const dataValues = @json($chartValues);

    // Bikin Gradient Warna Kuning Keren
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(255, 193, 7, 0.5)'); // Kuning Terang
    gradient.addColorStop(1, 'rgba(255, 193, 7, 0.0)'); // Transparan

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: dataValues,
                borderColor: '#ffc107', // Warna Garis Kuning
                backgroundColor: gradient, // Warna Isi Gradient
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#ffc107',
                pointRadius: 4,
                fill: true,
                tension: 0.4 // Garis lengkung halus
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }, // Hilangkan legenda biar bersih
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            // Format Rupiah di Tooltip
                            return ' Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.1)' }, // Grid tipis
                    ticks: { color: '#adb5bd' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#adb5bd' }
                }
            }
        }
    });
</script>
<style>
        body {
        background-color: #1a1a1a;
    }
</style>
@endsection