<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 1. Tampilkan Daftar Produk
    public function index()
    {
        $products = Product::where('user_id', Auth::id())->latest()->get();
        return view('seller.products.index', compact('products'));
    }

    // 2. Tampilkan Form Tambah
    public function create()
    {
        return view('seller.products.create');
    }

    // 3. Simpan Produk Baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required|numeric',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $request->file('gambar')->store('products', 'public');

        Product::create([
            'user_id' => Auth::id(),
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'ukuran' => $request->ukuran,
            'kondisi' => $request->kondisi,
            'deskripsi' => $request->deskripsi,
            'gambar' => $imagePath,
            'stok' => 1 // Default Thrift
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dijual!');
    }

    // --- FUNGSI BARU UNTUK EDIT & HAPUS ---

    // 4. Tampilkan Form Edit (Ambil data lama)
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        // Keamanan: Pastikan yg edit adalah pemilik barang
        if($product->user_id != Auth::id()) {
            abort(403);
        }

        return view('seller.products.edit', compact('product'));
    }

    // 5. Proses Update Database
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if($product->user_id != Auth::id()) { abort(403); }

        $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|max:2048', // Gambar boleh kosong kalau gak mau ganti
        ]);

        // Cek jika user upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama biar server gak penuh
            if($product->gambar) {
                Storage::delete('public/' . $product->gambar);
            }
            // Simpan gambar baru
            $imagePath = $request->file('gambar')->store('products', 'public');
            $product->gambar = $imagePath;
        }

        // Update data lainnya
        $product->update([
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'ukuran' => $request->ukuran,
            'kondisi' => $request->kondisi,
            'deskripsi' => $request->deskripsi,
            // gambar sudah dihandle di atas
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    // 6. Proses Hapus
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if($product->user_id != Auth::id()) { abort(403); }

        // Hapus file gambar dari penyimpanan
        if($product->gambar) {
            Storage::delete('public/' . $product->gambar);
        }

        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}