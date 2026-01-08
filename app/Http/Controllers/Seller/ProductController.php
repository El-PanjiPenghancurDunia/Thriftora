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
            'nama_produk' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|numeric|min:1', // Penjual bisa menentukan stok awal
            'gambar'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $request->file('gambar')->store('products', 'public');

        Product::create([
            'user_id'     => Auth::id(),
            'nama_produk' => $request->nama_produk,
            'harga'       => $request->harga,
            'stok'        => $request->stok, // Menggunakan input stok dari form
            'ukuran'      => $request->ukuran,
            'kondisi'     => $request->kondisi,
            'deskripsi'   => $request->deskripsi,
            'gambar'      => $imagePath,
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan ke etalase!');
    }

    // 4. Tampilkan Form Edit
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        // Keamanan: Pastikan yg edit adalah pemilik barang
        if($product->user_id != Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }

        return view('seller.products.edit', compact('product'));
    }

    // 5. Proses Update Database
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if($product->user_id != Auth::id()) { 
            abort(403); 
        }

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|numeric|min:0', // Stok boleh 0 (habis)
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Siapkan data untuk diupdate
        $data = [
            'nama_produk' => $request->nama_produk,
            'harga'       => $request->harga,
            'stok'        => $request->stok, // Update stok dari form
            'ukuran'      => $request->ukuran,
            'kondisi'     => $request->kondisi,
            'deskripsi'   => $request->deskripsi,
        ];

        // Cek jika user upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if($product->gambar) {
                Storage::delete('public/' . $product->gambar);
            }
            // Simpan gambar baru
            $data['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('seller.products.index')->with('success', 'Produk dan Stok berhasil diperbarui!');
    }

    // 6. Proses Hapus
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if($product->user_id != Auth::id()) { 
            abort(403); 
        }

        if($product->gambar) {
            Storage::delete('public/' . $product->gambar);
        }

        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}