<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // transaksiID
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // idPembeli
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // idProduct
            $table->decimal('total_harga', 12, 2);
            $table->date('tanggal_transaksi');
            // Status sesuai SKPL: Menunggu Bayar, Diproses, Dikirim, Selesai, Dibatalkan
            $table->string('status')->default('Menunggu Bayar'); 
            $table->string('nomor_resi')->nullable(); // Untuk fitur Input Resi Penjual
            $table->string('bukti_bayar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
