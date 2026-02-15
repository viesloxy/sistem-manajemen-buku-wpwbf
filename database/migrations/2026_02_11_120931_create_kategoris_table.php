<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->integer('idkategori')->autoIncrement(); 
            
            $table->string('nama_kategori', 100);
            
            $table->timestamps();
        });
    }
    
};
