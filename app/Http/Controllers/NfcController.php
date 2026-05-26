<?php

namespace App\Http\Controllers;

use App\Models\NfcTag;
use App\Models\AbsensiNfcLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NfcController extends Controller
{
    /**
     * Halaman scan NFC (Shared untuk Admin & Vendor)
     */
    public function scan()
    {
        // Cek permission: hanya admin dan vendor yang bisa akses
        if (!in_array(Auth::user()->role, ['admin', 'vendor'])) {
            abort(403, 'Unauthorized');
        }

        return view('nfc.scan');
    }

    /**
     * Proses hasil scan NFC
     */
    public function prosesScan(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string',
            'tipe_log' => 'required|in:masuk,keluar',
        ]);

        $serial = $request->serial_number;
        $tipeLog = $request->tipe_log;

        // Cari kartu berdasarkan serial number
        $tag = NfcTag::where('serial_number', $serial)->first();

        if (!$tag) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Kartu NFC tidak terdaftar'
            ], 404);
        }

        if (!$tag->isActive()) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Kartu NFC tidak aktif (Status: ' . $tag->getStatusLabelAttribute() . ')'
            ], 400);
        }

        // Catat log absensi
        $log = AbsensiNfcLog::create([
            'nfc_tag_id' => $tag->id,
            'user_id' => Auth::id(),
            'vendor_id' => Auth::user()->role === 'vendor' ? Auth::user()->vendor_id : $tag->vendor_id,
            'tipe_log' => $tipeLog,
            'scanned_at' => now()
        ]);

        return response()->json([
            'status' => 'ok',
            'pesan' => "Absensi {$tipeLog}: {$tag->nama_pemilik}",
            'data' => [
                'serial' => $tag->serial_number,
                'nama' => $tag->nama_pemilik,
                'tipe' => $tag->getTypeLabelAttribute(),
                'waktu' => $log->getScannedTimeAttribute()
            ]
        ]);
    }

    /**
     * Lihat log absensi sendiri (untuk vendor/admin)
     */
    public function log()
    {
        $user = Auth::user();

        if ($user->role === 'vendor') {
            // Vendor hanya lihat log vendor sendiri
            $logs = AbsensiNfcLog::with(['nfcTag', 'user'])
                ->byVendor($user->vendor_id)
                ->orderBy('scanned_at', 'desc')
                ->paginate(20);
        } else {
            // Admin lihat semua log
            $logs = AbsensiNfcLog::with(['nfcTag', 'user', 'vendor'])
                ->orderBy('scanned_at', 'desc')
                ->paginate(20);
        }

        return view('nfc.logs.index', compact('logs'));
    }
}
