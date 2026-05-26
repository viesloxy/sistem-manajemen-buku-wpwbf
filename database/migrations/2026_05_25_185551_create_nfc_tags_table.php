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
        Schema::create('nfc_tags', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number', 255)->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('nama_pemilik', 255)->nullable();
            $table->enum('tipe', ['staff', 'vendor', 'admin'])->default('staff');
            $table->enum('status', ['aktif', 'nonaktif', 'hilang'])->default('aktif');
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');

            // Indexes
            $table->index('serial_number');
            $table->index('user_id');
            $table->index('vendor_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nfc_tags');
    }
};
