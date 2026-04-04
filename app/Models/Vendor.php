<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_vendor',
    ];

    // Relasi: 1 Vendor dimiliki oleh 1 User (Akun)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: 1 Vendor punya banyak Menu
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}