<?php

namespace App\Http\Controllers;

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
    public function orders()
    {
        // Ambil transaksi dimana produknya adalah milik penjual yang login
        $orders = Transaction::whereHas('product', function($query) {
                        $query->where('user_id', Auth::id());
                    })
                    ->with(['product', 'user']) // Load data produk & pembeli
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('seller.orders', compact('orders'));
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
}