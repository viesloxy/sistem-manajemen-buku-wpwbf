<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'total',
        'metode_bayar',
        'status_bayar',
        'snap_token',
    ];

    // Relasi: 1 Pesanan punya banyak Detail Pesanan
    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}