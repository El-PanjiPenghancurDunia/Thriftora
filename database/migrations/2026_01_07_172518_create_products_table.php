<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Jika Anda pakai kategori, biarkan baris bawah ini. Jika error, hapus saja.
            $table->foreignId('category_id')->nullable(); 
            
            $table->string('nama_produk');
            $table->decimal('harga', 12, 2);
            $table->string('ukuran'); // <--- INI YANG TADI HILANG
            $table->string('kondisi');
            $table->integer('stok');
            $table->text('deskripsi');
            $table->string('gambar')->nullable(); // Pastikan namanya 'gambar', bukan 'foto'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};