<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // KITA BUKA IZIN UNTUK SEMUA KOLOM INI:
    protected $fillable = [
        'user_id',
        'product_id',
        'total_harga',
        'tanggal_transaksi',
        'status',
        
        // Data Tambahan untuk Midtrans & Ongkir
        'snap_token',
        'payment_status',
        'courier',
        'service',
        'shipping_cost',
        'nomor_resi'
    ];

    // Relasi ke Produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke Pembeli
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}