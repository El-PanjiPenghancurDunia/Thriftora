<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    // 1. Dashboard Penjual
    public function index()
    {
        return view('seller.dashboard');
    }

    // 2. Lihat Daftar Pesanan Masuk
// Ganti function orders() yang lama dengan ini:
public function orders(Request $request)
{
    // 1. Atur Default Tanggal
    $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

    // 2. Query Dasar (Transaksi milik user yang login + Filter Tanggal)
    // Kita simpan ini sebagai "Base Query" yang bersih
    $query = \App\Models\Transaction::whereHas('product', function($q) {
        $q->where('user_id', Auth::id());
    })->whereDate('created_at', '>=', $startDate)
      ->whereDate('created_at', '<=', $endDate);

    // 3. Ambil Data Tabel (Gunakan clone agar $query asli tidak berubah)
    $orders = (clone $query)->with(['user', 'product'])->latest()->get();

    // 4. Hitung Ringkasan (Cards) - GUNAKAN CLONE DI SETIAP BARIS
    $totalPendapatan = (clone $query)->whereIn('status', ['Dibayar', 'Dikirim', 'Selesai'])->sum('total_harga');
    
    $totalPesanan    = (clone $query)->count();
    
    $pesananSukses   = (clone $query)->where('status', 'Selesai')->count();
    
    // Ini yang tadi error jadi 0, sekarang sudah diperbaiki dengan clone
    $pesananPending  = (clone $query)->where('status', 'Menunggu Pembayaran')->count();

    // 5. Siapkan Data untuk Chart (Grafik Harian)
    $chartData = (clone $query)->whereIn('status', ['Dibayar', 'Dikirim', 'Selesai'])
        ->select(
            DB::raw('DATE(created_at) as date'), 
            DB::raw('SUM(total_harga) as total')
        )
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

    // Format array untuk Chart.js
    $chartLabels = [];
    $chartValues = [];

    // Loop dari start_date ke end_date agar grafik tidak bolong
    $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
    foreach ($period as $date) {
        $dateStr = $date->format('Y-m-d');
        $chartLabels[] = $date->format('d M'); // Label Sumbu X (Tgl)
        
        // Cek apakah ada transaksi di tanggal ini
        $data = $chartData->firstWhere('date', $dateStr);
        $chartValues[] = $data ? $data->total : 0; // Data Sumbu Y (Rupiah)
    }

    return view('seller.orders', compact(
        'orders', 
        'startDate', 
        'endDate',
        'totalPendapatan',
        'totalPesanan',
        'pesananSukses',
        'pesananPending',
        'chartLabels',
        'chartValues'
    ));
}

    // 3. Proses Kirim Barang (Input Resi)
    public function shipOrder(Request $request, $id)
    {
        $request->validate([
            'nomor_resi' => 'required|string|max:50'
        ]);

        $trx = Transaction::findOrFail($id);

        // Pastikan barang ini memang milik penjual yang login (Keamanan)
        if($trx->product->user_id != Auth::id()) {
            return abort(403, 'Anda tidak berhak mengelola pesanan ini.');
        }

        // Update Data
        $trx->update([
            'status' => 'Dikirim',
            'nomor_resi' => $request->nomor_resi
        ]);

        return redirect()->back()->with('success', 'Resi berhasil diinput! Pesanan berubah menjadi DIKIRIM.');
    }
    // Tambahkan method ini di dalam class SellerController

public function showOrder($id)
{
    // Ambil transaksi beserta data pembeli (user) dan produk
    $trx = \App\Models\Transaction::with(['user', 'product'])->findOrFail($id);

    // Keamanan: Pastikan produk yang dibeli adalah milik penjual yang sedang login
    if ($trx->product->user_id != Auth::id()) {
        abort(403, 'Pesanan ini bukan milik toko Anda.');
    }

    return view('seller.orders.show', compact('trx'));
}
}