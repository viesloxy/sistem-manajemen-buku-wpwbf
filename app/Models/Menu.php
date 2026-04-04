<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'nama_menu',
        'harga',
        'path_gambar',
    ];

    // Relasi: Menu ini milik 1 Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Relasi: Menu ini bisa ada di banyak Detail Pesanan
    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}