<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke User (Pembeli)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Relasi ke Produk
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Data Dasar
            $table->integer('total_harga'); // Total bayar (Barang + Ongkir)
            $table->date('tanggal_transaksi');
            $table->string('status'); // Menunggu Pembayaran, Dibayar, Dikirim, Selesai
            
            // Data Midtrans & Pembayaran
            $table->string('snap_token')->nullable(); // Token untuk pop-up midtrans
            $table->string('payment_status')->default('pending'); // pending, settlement, expire
            
            // Data Pengiriman (RajaOngkir)
            $table->string('courier')->nullable(); // jne, pos, tiki
            $table->string('service')->nullable(); // REG, YES, OKE
            $table->integer('shipping_cost')->default(0); // Biaya ongkir
            $table->string('nomor_resi')->nullable(); // Diisi penjual nanti
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};