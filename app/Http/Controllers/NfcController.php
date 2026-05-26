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
        if (!in_array(Auth::user()->role, ['admin', 'vendor'])) {
            abort(403, 'Unauthorized');
        }

        $recentScansQuery = AbsensiNfcLog::with(['nfcTag', 'user'])
            ->orderBy('scanned_at', 'desc')
            ->limit(10);

        if (Auth::user()->role === 'vendor') {
            $recentScansQuery->where('vendor_id', Auth::user()->vendor_id);
        }

        $recentScans = $recentScansQuery->get();

        return view('nfc.scan', compact('recentScans'));
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
    public function log(Request $request)
    {
        $user = Auth::user();

        $query = AbsensiNfcLog::with(['nfcTag', 'user', 'vendor'])
            ->orderBy('scanned_at', 'desc');

        if ($user->role === 'vendor') {
            $query->where('vendor_id', $user->vendor_id);
        }

        if ($request->has('tipe_log') && $request->tipe_log) {
            $query->where('tipe_log', $request->tipe_log);
        }

        if ($request->has('start_date') && $request->start_date) {
            $endDate = $request->has('end_date') && $request->end_date
                ? $request->end_date
                : now()->format('Y-m-d');
            $query->whereDate('scanned_at', '>=', $request->start_date)
                  ->whereDate('scanned_at', '<=', $endDate);
        }

        $logs = $query->paginate(20);

        return view('nfc.logs.index', compact('logs'));
    }
}
