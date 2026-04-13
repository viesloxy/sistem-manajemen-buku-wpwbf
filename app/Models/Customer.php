<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'no_hp',
        'email',
        'alamat',
        'foto_blob',
        'foto_path',
    ];

    /**
     * Get the foto URL (prioritas blob, fallback ke path)
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto_blob) {
            return $this->foto_blob;
        }
        if ($this->foto_path && file_exists(public_path($this->foto_path))) {
            return asset($this->foto_path);
        }
        return null;
    }
}
