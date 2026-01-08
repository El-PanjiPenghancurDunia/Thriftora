<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;

class AdminController extends Controller
{
    // 1. Dashboard Admin (Statistik)
    public function index()
    {
        $totalUser = User::where('role', '!=', 'admin')->count();
        $totalProduk = Product::count();
        $totalTransaksi = Transaction::where('status', '!=', 'Menunggu Pembayaran')->count();
        $totalPendapatan = Transaction::where('status', '!=', 'Menunggu Pembayaran')->sum('total_harga');

        return view('admin.dashboard', compact('totalUser', 'totalProduk', 'totalTransaksi', 'totalPendapatan'));
    }

    // 2. Kelola User
    public function users()
    {
        $users = User::where('role', '!=', 'admin')->latest()->get();
        return view('admin.users', compact('users'));
    }

    public function destroyUser($id)
    {
        User::destroy($id);
        return redirect()->back()->with('success', 'User berhasil dihapus dari sistem.');
    }

    // 3. Kelola Semua Produk (Moderasi)
    public function products()
    {
        $products = Product::with('user')->latest()->get();
        return view('admin.products', compact('products'));
    }

    public function destroyProduct($id)
    {
        Product::destroy($id); // Admin berhak menghapus produk siapapun
        return redirect()->back()->with('success', 'Produk berhasil dihapus (Takedown).');
    }

    // 4. Laporan Transaksi
    public function transactions()
    {
        $transactions = Transaction::with(['user', 'product.user'])->latest()->get();
        return view('admin.transactions', compact('transactions'));
    }
}