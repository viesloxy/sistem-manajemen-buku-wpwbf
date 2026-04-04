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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id(); // Pengganti idpesanan
            $table->string('nama'); // Nama customer, misal: Guest_0000001
            $table->integer('total');
            $table->string('metode_bayar')->nullable();
            // Status bayar kita buat string (Pending, Lunas, Batal) agar mudah dibaca
            $table->string('status_bayar')->default('Pending'); 
            $table->string('snap_token')->nullable(); // Wajib untuk integrasi Midtrans
            $table->timestamps(); // Pengganti timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
