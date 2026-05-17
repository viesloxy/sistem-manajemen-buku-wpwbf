<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();          // Format: "001", "002", dst.
            $table->string('nama');                    // Nama lengkap pemesan
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('cascade');
                                                  // Vendor yang dituju (nullable jika tidak diperlukan)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                                                  // Admin yang memanggil (nullable, guest daftar tanpa login)
            $table->enum('status', ['menunggu', 'dipanggil', 'terlambat', 'selesai'])
                  ->default('menunggu');
            $table->timestamp('dipanggil_pada')->nullable();        // Kapan nomor dipanggil
            $table->timestamp('keterlambatan_pada')->nullable();    // Kapan ditandai terlambat
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};
