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
        Schema::create('absensi_nfc_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nfc_tag_id');
            $table->unsignedBigInteger('user_id')->comment('Operator yang melakukan scan');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->enum('tipe_log', ['masuk', 'keluar'])->default('masuk');
            $table->timestamp('scanned_at')->useCurrent();
            $table->timestamps();

            // Foreign keys
            $table->foreign('nfc_tag_id')->references('id')->on('nfc_tags')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');

            // Indexes
            $table->index('nfc_tag_id');
            $table->index('user_id');
            $table->index('vendor_id');
            $table->index('tipe_log');
            $table->index('scanned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_nfc_logs');
    }
};
