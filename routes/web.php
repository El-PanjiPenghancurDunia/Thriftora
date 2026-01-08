<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Seller\ProductController as SellerProductController; 
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// --- HALAMAN DEPAN ---
Route::get('/', function (Request $request) {
    $query = Product::where('stok', '>', 0);
    if($request->has('search')) {
        $query->where('nama_produk', 'like', '%' . $request->search . '%');
    }
    $products = $query->latest()->get();
    return view('welcome', compact('products'));
});

// Route Detail Produk Publik
Route::get('/product/{id}', function ($id) {
    $product = \App\Models\Product::with(['user', 'reviews.user'])->findOrFail($id);
    return view('product_detail', compact('product'));
})->name('product.detail');

// --- AUTHENTICATION ---
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');

// --- GRUP ROUTE PENJUAL ---
Route::middleware(['auth', 'role:penjual'])->group(function () {
    Route::get('/seller/dashboard', [SellerController::class, 'index'])->name('seller.dashboard');
    Route::resource('seller/products', SellerProductController::class)->names('seller.products');
    Route::get('/seller/orders', [SellerController::class, 'orders'])->name('seller.orders');
    Route::post('/seller/orders/{id}/ship', [SellerController::class, 'shipOrder'])->name('seller.orders.ship');
});

// --- GRUP ROUTE ADMIN ---
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::delete('/admin/products/{id}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');
    Route::get('/admin/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
});

// --- GRUP ROUTE PEMBELI ---
Route::middleware(['auth', 'role:pembeli'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'store'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/checkout', [TransactionController::class, 'viewCheckout'])->name('checkout.view');
    Route::post('/api/check-ongkir', [TransactionController::class, 'checkOngkir'])->name('api.checkOngkir');
    Route::post('/checkout-process', [TransactionController::class, 'process'])->name('checkout.process');
    Route::get('/my-orders', [TransactionController::class, 'history'])->name('pembeli.orders');
    Route::post('/payment/fake-success', [TransactionController::class, 'fakePaymentSuccess'])->name('payment.fakeSuccess');
    
    Route::get('/my-orders/{id}', [TransactionController::class, 'show'])->name('pembeli.orders.show');
    
    // Profil & Review
    Route::post('/my-orders/{id}/complete', [App\Http\Controllers\ProfileController::class, 'completeOrder'])->name('orders.complete');
    Route::post('/products/{id}/review', [App\Http\Controllers\ProfileController::class, 'submitReview'])->name('products.review');
});
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');


// --- JURUS DARURAT 1: RESET TRANSAKSI ---
Route::get('/fix-database-now', function () {
    try {
        Schema::dropIfExists('transactions');
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('total_harga');
            $table->date('tanggal_transaksi');
            $table->string('status'); 
            $table->string('snap_token')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('courier')->nullable();
            $table->string('service')->nullable();
            $table->integer('shipping_cost')->default(0);
            $table->string('nomor_resi')->nullable();
            $table->timestamps();
        });
        return "SUKSES RESET TABEL TRANSAKSI";
    } catch (\Exception $e) {
        return "ERROR: " . $e->getMessage();
    }
});

// --- JURUS DARURAT 2: TAMBAH KOLOM FOTO (PISAH DARI YANG ATAS) ---
Route::get('/emergency/add-photo-column', function () {
    try {
        if (!Schema::hasColumn('users', 'profile_photo')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('profile_photo')->nullable()->after('password');
            });
            return "BERHASIL! Kolom 'profile_photo' sudah ditambahkan.";
        }
        return "Kolom 'profile_photo' SUDAH ADA sebelumnya.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// --- JURUS DARURAT 3: RESET TABEL REVIEWS ---
Route::get('/fix-reviews-table', function () {
    try {
        // 1. Hapus tabel lama yang rusak
        Schema::dropIfExists('reviews');
        
        // 2. Buat ulang tabel dengan struktur BENAR
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');    // <--- INI YANG TADI HILANG
            $table->unsignedBigInteger('product_id'); // <--- INI JUGA PENTING
            $table->integer('rating');
            $table->text('comment');
            $table->timestamps();
        });

        return "SUKSES! Tabel reviews berhasil dibuat ulang dengan kolom user_id.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/emergency/add-quantity', function () {
    try {
        // Tambah quantity ke tabel keranjang
        if (!Schema::hasColumn('carts', 'quantity')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->integer('quantity')->default(1)->after('product_id');
            });
        }
        // Tambah quantity ke tabel transaksi (penting untuk histori & stok)
        if (!Schema::hasColumn('transactions', 'quantity')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->integer('quantity')->default(1)->after('product_id');
            });
        }
        return "BERHASIL! Kolom quantity sudah ditambahkan ke Carts dan Transactions.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Tambahkan sementara di web.php jika belum ada
Route::get('/emergency/fix-cart-column', function () {
    try {
        // Jika ada kolom 'jumlah', kita ubah jadi 'quantity'
        if (Schema::hasColumn('carts', 'jumlah')) {
            Schema::table('carts', function (Illuminate\Database\Schema\Blueprint $table) {
                $table->renameColumn('jumlah', 'quantity');
            });
            return "BERHASIL! Nama kolom 'jumlah' sudah diubah menjadi 'quantity'.";
        }
        
        // Jika belum ada kolom quantity sama sekali, buat baru
        if (!Schema::hasColumn('carts', 'quantity')) {
            Schema::table('carts', function (Illuminate\Database\Schema\Blueprint $table) {
                $table->integer('quantity')->default(1)->after('product_id');
            });
            return "BERHASIL! Kolom 'quantity' telah ditambahkan.";
        }

        return "Database sudah OK. Kolom 'quantity' sudah tersedia.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});