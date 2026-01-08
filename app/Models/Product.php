<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',      // Siapa penjualnya
        'nama_produk',
        'deskripsi',
        'harga',
        'ukuran',       // S, M, L, XL
        'kondisi',      // Baru, Bekas (Like New), Defect
        'stok',
        'gambar',       // Nama file foto
    ];

    // Relasi: Produk milik satu User (Penjual)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}