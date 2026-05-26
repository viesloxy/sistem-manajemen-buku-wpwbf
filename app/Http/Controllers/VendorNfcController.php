<?php

namespace App\Http\Controllers;

use App\Models\NfcTag;
use App\Models\AbsensiNfcLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorNfcController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:vendor');
    }

    /**
     * Halaman scan NFC untuk vendor.
     */
    public function scan()
    {
        $todayLogs = AbsensiNfcLog::with(['nfcTag'])
            ->where('vendor_id', Auth::user()->vendor_id)
            ->whereDate('scanned_at', now()->toDateString())
            ->orderBy('scanned_at', 'desc')
            ->limit(10)
            ->get();

        return view('vendor.nfc.scan', compact('todayLogs'));
    }

    /**
     * Proses hasil scan NFC (Vendor).
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

        if ($tag->vendor_id && $tag->vendor_id !== Auth::user()->vendor_id) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Kartu NFC tidak terdaftar untuk vendor Anda'
            ], 403);
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
            'vendor_id' => Auth::user()->vendor_id,
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
     * Lihat log absensi vendor sendiri.
     */
    public function log(Request $request)
    {
        $query = AbsensiNfcLog::with(['nfcTag', 'user'])
            ->where('vendor_id', Auth::user()->vendor_id)
            ->orderBy('scanned_at', 'desc');

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

        $logs = $query->paginate(50);

        return view('vendor.nfc.logs', compact('logs'));
    }

    /**
     * Daftarkan kartu baru untuk staff vendor.
     */
    public function createTag()
    {
        $staff = User::where('vendor_id', Auth::user()->vendor_id)
            ->where('role', 'staff')
            ->get();

        return view('vendor.nfc.tags.create', compact('staff'));
    }

    /**
     * Simpan kartu baru untuk staff vendor.
     */
    public function storeTag(Request $request)
    {
        $validated = $request->validate([
            'serial_number' => 'required|string|unique:nfc_tags,serial_number',
            'user_id' => 'nullable|exists:users,id',
            'nama_pemilik' => 'required|string|max:255',
            'tipe' => 'required|in:staff',
        ]);

        $validated['vendor_id'] = Auth::user()->vendor_id;
        $validated['status'] = 'aktif';

        NfcTag::create($validated);

        return redirect()->route('vendor.nfc.logs')
            ->with('success', 'Kartu NFC staff berhasil didaftarkan');
    }
}
