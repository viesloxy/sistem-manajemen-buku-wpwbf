<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        // Tabel Header Penjualan
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id('id_penjualan');
            $table->integer('total');
            $table->timestamp('timestamp')->useCurrent();
            $table->timestamps();
        });

        // Tabel Detail Penjualan
        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->id('id_penjualan_detail');
            $table->unsignedBigInteger('id_penjualan');
            $table->string('id_barang', 8);
            $table->integer('jumlah');
            $table->integer('subtotal');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_penjualan')->references('id_penjualan')->on('penjualan')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('penjualan_detail');
        Schema::dropIfExists('penjualan');
    }
};
