<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Antrian extends Model
{
    protected $fillable = [
        'nomor',
        'nama',
        'vendor_id',
        'user_id',
        'status',
        'dipanggil_pada',
        'keterlambatan_pada',
    ];

    protected $casts = [
        'dipanggil_pada'     => 'datetime',
        'keterlambatan_pada' => 'datetime',
    ];

    // Relasi ke vendor
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    // Relasi ke admin yang memanggil
    public function pemanggil(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope: hanya antrian hari ini
    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', now()->toDateString());
    }

    // Scope: status menunggu
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    // Scope: status dipanggil
    public function scopeDipanggil($query)
    {
        return $query->where('status', 'dipanggil');
    }

    // Scope: status terlambat
    public function scopeTerlambat($query)
    {
        return $query->where('status', 'terlambat');
    }

    // Scope: status selesai
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    // Scope: urut berdasarkan nomor (ascending)
    public function scopeUrut($query)
    {
        return $query->orderBy('nomor', 'asc');
    }
}