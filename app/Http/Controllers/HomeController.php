<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product; // <--- PENTING: Panggil Model Produk

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $role = Auth::user()->role;

        if ($role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role == 'penjual') {
            return redirect()->route('seller.dashboard');
        } else {
            // --- LOGIKA PEMBELI + SEARCH ---
            $query = Product::where('stok', '>', 0);

            if($request->has('search')) {
                $query->where('nama_produk', 'like', '%' . $request->search . '%');
            }

            $products = $query->latest()->get();
            
            return view('home', compact('products')); 
        }
    }
}