<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // 1. Tampilkan Halaman Checkout
    public function viewCheckout()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        
        if($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        // DATA KOTA PALSU (Manual, tidak perlu API RajaOngkir)
        $cities = [
            ['city_id' => 1, 'type' => 'Kota', 'city_name' => 'Jakarta Pusat'],
            ['city_id' => 2, 'type' => 'Kota', 'city_name' => 'Semarang'],
            ['city_id' => 3, 'type' => 'Kota', 'city_name' => 'Surabaya'],
            ['city_id' => 4, 'type' => 'Kota', 'city_name' => 'Bandung'],
            ['city_id' => 5, 'type' => 'Kota', 'city_name' => 'Medan'],
        ];

        return view('pembeli.checkout', compact('carts', 'cities'));
    }

    // 2. API Cek Ongkir (PALSU / SIMULASI)
    public function checkOngkir(Request $request)
    {
        // Kita buat harga ongkir ngasal berdasarkan kurir yang dipilih
        $harga = 20000; // Harga dasar
        
        if($request->courier == 'jne') {
            $services = [
                ['service' => 'JNE REG', 'cost' => 22000, 'etd' => '2-3 Hari'],
                ['service' => 'JNE YES', 'cost' => 35000, 'etd' => '1 Hari'],
            ];
        } elseif($request->courier == 'pos') {
            $services = [
                ['service' => 'POS Kilat', 'cost' => 18000, 'etd' => '3-4 Hari'],
            ];
        } else {
            $services = [
                ['service' => 'TIKI ECO', 'cost' => 15000, 'etd' => '4-5 Hari'],
                ['service' => 'TIKI ONS', 'cost' => 30000, 'etd' => '1 Hari'],
            ];
        }

        return response()->json($services);
    }

    // 3. Proses Checkout (GENERATE TOKEN PALSU)
    public function process(Request $request)
    {
        // Ambil Keranjang
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        
        // Hitung Total
        $totalBarang = $carts->sum(function($item) { return $item->product->harga; });
        $ongkir = (int) $request->shipping_cost; 
        
        // Buat Token Asal-Asalan (Supaya tombol bayar nanti nyala)
        $fakeToken = 'TOKEN-DUMMY-' . time() . '-' . rand(100,999);

        // Simpan Transaksi
        foreach ($carts as $item) {
            Transaction::create([
                'user_id' => Auth::id(),
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'total_harga' => $item->product->harga + ($ongkir / $carts->count()), 
                'tanggal_transaksi' => now(),
                'status' => 'Menunggu Pembayaran',
                'snap_token' => $fakeToken, // <--- PENTING: Token Palsu Disimpan
                'courier' => $request->courier_service, 
                'shipping_cost' => $ongkir,
                'payment_status' => 'pending'
            ]);
            
           
        }

        // Hapus Keranjang
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('pembeli.orders')->with('success', 'Checkout Berhasil! Silakan Bayar.');
    }

    // 4. Halaman Riwayat
    public function history()
    {
        $transactions = Transaction::with('product')
                        ->where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('pembeli.orders', compact('transactions'));
    }

    // 5. Pembayaran Palsu Sukses
public function fakePaymentSuccess(Request $request)
{
    // Ambil semua transaksi dengan token yang sama
    $transactions = Transaction::where('snap_token', $request->snap_token)->get();

    if ($transactions->count() > 0) {
        foreach ($transactions as $trx) {
            if ($trx->status == 'Menunggu Pembayaran') {
                // 1. Update status transaksi
                $trx->update(['status' => 'Dibayar']);

                // 2. Kurangi stok produk sesuai quantity yang dibeli
                $product = $trx->product;
                if ($product->stok >= $trx->quantity) {
                    $product->decrement('stok', $trx->quantity);
                }
            }
        }
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 404);
}

    // ... fungsi lainnya ...

    // 5. Tampilkan Detail Transaksi (Fungsi Baru)
    public function show($id)
    {
        // Cari transaksi berdasarkan ID dan pastikan milik user yang login (Keamanan)
        $trx = Transaction::with(['product.user', 'user'])
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

        return view('pembeli.detail', compact('trx'));
    }

}