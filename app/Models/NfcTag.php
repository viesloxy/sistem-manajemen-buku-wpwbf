<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NfcTag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'serial_number',
        'user_id',
        'vendor_id',
        'nama_pemilik',
        'tipe',
        'status',
    ];

    /**
     * Get the user that owns the NFC tag.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vendor that owns the NFC tag.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the absensi logs for the NFC tag.
     */
    public function absensiLogs(): HasMany
    {
        return $this->hasMany(AbsensiNfcLog::class, 'nfc_tag_id');
    }

    /**
     * Scope a query to only include active tags.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('tipe', $type);
    }

    /**
     * Scope a query to filter by vendor.
     */
    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    /**
     * Check if tag is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'aktif';
    }

    /**
     * Get formatted type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->tipe) {
            'staff' => 'Staff',
            'vendor' => 'Vendor',
            'admin' => 'Admin',
            default => 'Unknown',
        };
    }

    /**
     * Get formatted status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
            'hilang' => 'Hilang',
            default => 'Unknown',
        };
    }

    /**
     * Get status badge HTML.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'aktif' => '<span class="badge bg-success">Aktif</span>',
            'nonaktif' => '<span class="badge bg-secondary">Nonaktif</span>',
            'hilang' => '<span class="badge bg-danger">Hilang</span>',
            default => '<span class="badge bg-warning">Unknown</span>',
        };
    }
}
