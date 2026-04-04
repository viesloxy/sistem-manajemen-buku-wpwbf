<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    use HasFactory;

    // Supaya Laravel tahu nama tabel yang benar
    protected $table = 'detail_pesanans'; 

    protected $fillable = [
        'pesanan_id',
        'menu_id',
        'jumlah',
        'harga',
        'subtotal',
        'catatan',
    ];

    // Relasi: Detail Pesanan ini milik 1 Pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    // Relasi: Detail Pesanan ini menunjuk ke 1 Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}