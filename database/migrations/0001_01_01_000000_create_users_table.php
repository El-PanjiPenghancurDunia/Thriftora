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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Ini userID
            $table->string('name'); // nama
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'penjual', 'pembeli'])->default('pembeli'); // role sesuai aktor
            $table->text('alamat_pengiriman')->nullable(); // Sesuai atribut User di SKPL
            $table->string('no_hp')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
