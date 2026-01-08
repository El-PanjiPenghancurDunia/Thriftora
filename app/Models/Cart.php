<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    // INI YANG KURANG TADI:
    // Kita izinkan kolom ini untuk diisi otomatis
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity'
    ];

    // Relasi: Cart menyimpan 1 Produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi: Cart milik 1 User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}