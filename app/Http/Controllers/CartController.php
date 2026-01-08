<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // 1. Tampilkan Isi Keranjang
    public function index()
    {
        // Ambil data keranjang milik user yang login, beserta data produknya
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        
        // Hitung total bayar
        $total = $carts->sum(function($item) {
            return $item->product->harga * $item->jumlah;
        });

        return view('cart.index', compact('carts', 'total'));
    }

    // 2. Tambah Barang ke Keranjang
    public function store(Request $request, $productId)
    {
        // Cek apakah barang sudah ada di keranjang user ini?
        $existingCart = Cart::where('user_id', Auth::id())
                            ->where('product_id', $productId)
                            ->first();

        if ($existingCart) {
            // Jika sudah ada, jangan ditambah (karena Thrift stoknya cuma 1)
            return redirect()->back()->with('error', 'Barang ini sudah ada di keranjangmu!');
        }

        // Simpan ke database
        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'jumlah' => 1 // Default 1 karena barang thrift unik
        ]);

        return redirect()->route('cart.index')->with('success', 'Berhasil masuk keranjang!');
    }

    // 3. Hapus Barang dari Keranjang
    public function destroy($id)
    {
        Cart::destroy($id);
        return redirect()->back()->with('success', 'Barang dihapus dari keranjang.');
    }
}