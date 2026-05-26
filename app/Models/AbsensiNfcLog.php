<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsensiNfcLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nfc_tag_id',
        'user_id',
        'vendor_id',
        'tipe_log',
        'scanned_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    /**
     * Get the NFC tag that owns the log.
     */
    public function nfcTag(): BelongsTo
    {
        return $this->belongsTo(NfcTag::class, 'nfc_tag_id');
    }

    /**
     * Get the user (operator) that owns the log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vendor that owns the log.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Scope a query to filter by log type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('tipe_log', $type);
    }

    /**
     * Scope a query to filter by vendor.
     */
    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('scanned_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to get today's logs.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('scanned_at', today());
    }

    /**
     * Get formatted type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->tipe_log) {
            'masuk' => 'Masuk',
            'keluar' => 'Keluar',
            default => 'Unknown',
        };
    }

    /**
     * Get type badge HTML.
     */
    public function getTypeBadgeAttribute(): string
    {
        return match($this->tipe_log) {
            'masuk' => '<span class="badge bg-success">Masuk</span>',
            'keluar' => '<span class="badge bg-warning">Keluar</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }

    /**
     * Get formatted scanned time.
     */
    public function getFormattedScannedAtAttribute(): string
    {
        return $this->scanned_at->format('d/m/Y H:i:s');
    }

    /**
     * Get scanned time only (HH:MM:SS).
     */
    public function getScannedTimeAttribute(): string
    {
        return $this->scanned_at->format('H:i:s');
    }
}
