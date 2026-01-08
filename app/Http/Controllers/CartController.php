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
    $carts = Cart::with('product')->where('user_id', Auth::id())->get();

    // Hitung total: Harga Produk dikali Jumlah (Quantity)
    $total = $carts->sum(function($item) {
        return $item->product->harga * $item->quantity;
    });

    return view('cart.index', compact('carts', 'total'));
}

    // 2. Tambah Barang ke Keranjang
    public function store(Request $request, $id)
{
    $product = Product::findOrFail($id);
    
    // Ambil jumlah dari input, jika tidak ada default 1
    $qty = $request->input('quantity', 1);

    // Validasi Stok
    if ($product->stok < $qty) {
        return redirect()->back()->with('error', 'Stok tidak mencukupi!');
    }

    // Cek apakah barang sudah ada di keranjang user
    $cartItem = \App\Models\Cart::where('user_id', Auth::id())
                                ->where('product_id', $id)
                                ->first();

    if ($cartItem) {
        // Jika sudah ada, tambahkan jumlahnya
        $newQty = $cartItem->quantity + $qty;
        if ($newQty > $product->stok) {
            return redirect()->back()->with('error', 'Total di keranjang melebihi stok!');
        }
        $cartItem->update(['quantity' => $newQty]);
    } else {
        // Jika belum ada, buat baru
        \App\Models\Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $id,
            'quantity' => $qty,
        ]);
    }

    return redirect()->route('cart.index')->with('success', 'Berhasil ditambahkan ke keranjang!');
}

    // 3. Hapus Barang dari Keranjang
    public function destroy($id)
    {
        Cart::destroy($id);
        return redirect()->back()->with('success', 'Barang dihapus dari keranjang.');
    }
}