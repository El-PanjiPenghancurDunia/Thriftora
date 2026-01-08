<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah Berat di Produk
        Schema::table('products', function (Blueprint $table) {
            $table->integer('berat')->default(1000)->after('harga'); // Dalam gram
        });

        // 2. Tambah City ID di User (Untuk Lokasi Pembeli)
        Schema::table('users', function (Blueprint $table) {
            $table->integer('city_id')->nullable()->after('alamat_pengiriman'); 
        });

        // 3. Tambah Detail Ongkir & Midtrans di Transaksi
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('courier')->nullable()->after('total_harga'); // jne, pos, tiki
            $table->string('service')->nullable()->after('courier'); // REG, YES, OKE
            $table->integer('shipping_cost')->default(0)->after('service');
            $table->string('snap_token')->nullable()->after('status'); // Token Midtrans
            $table->string('payment_status')->default('pending')->after('snap_token'); // 1=pending, 2=success, 3=expire
        });
    }

    public function down(): void
    {
        // Hapus kolom jika rollback (Opsional)
    }
};