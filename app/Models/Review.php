<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // --- INI YANG KURANG TADI ---
    // Kita harus mengizinkan kolom-kolom ini diisi
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment'
    ];

    // Relasi ke User (Penulis Review)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Produk (Barang yang direview)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}