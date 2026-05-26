<?php

namespace App\Http\Controllers;

use App\Models\NfcTag;
use App\Models\AbsensiNfcLog;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNfcController extends Controller
{
    /**
     * Display a listing of NFC tags.
     */
    public function indexTags()
    {
        $tags = NfcTag::with(['user', 'vendor'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('nfc.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new NFC tag.
     */
    public function createTag()
    {
        $users = User::where('role', '!=', 'customer')->get();
        $vendors = Vendor::all();

        return view('nfc.tags.create', compact('users', 'vendors'));
    }

    /**
     * Store a newly created NFC tag in storage.
     */
    public function storeTag(Request $request)
    {
        $validated = $request->validate([
            'serial_number' => 'required|string|unique:nfc_tags,serial_number',
            'user_id' => 'nullable|exists:users,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'nama_pemilik' => 'required|string|max:255',
            'tipe' => 'required|in:staff,vendor,admin',
            'status' => 'required|in:aktif,nonaktif,hilang',
        ]);

        NfcTag::create($validated);

        return redirect()->route('nfc.tags.index')
            ->with('success', 'Kartu NFC berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified NFC tag.
     */
    public function editTag(NfcTag $nfcTag)
    {
        $users = User::where('role', '!=', 'customer')->get();
        $vendors = Vendor::all();

        return view('nfc.tags.edit', compact('nfcTag', 'users', 'vendors'));
    }

    /**
     * Update the specified NFC tag in storage.
     */
    public function updateTag(Request $request, NfcTag $nfcTag)
    {
        $validated = $request->validate([
            'serial_number' => 'required|string|unique:nfc_tags,serial_number,' . $nfcTag->id,
            'user_id' => 'nullable|exists:users,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'nama_pemilik' => 'required|string|max:255',
            'tipe' => 'required|in:staff,vendor,admin',
            'status' => 'required|in:aktif,nonaktif,hilang',
        ]);

        $nfcTag->update($validated);

        return redirect()->route('nfc.tags.index')
            ->with('success', 'Kartu NFC berhasil diperbarui');
    }

    /**
     * Remove the specified NFC tag from storage.
     */
    public function destroyTag(NfcTag $nfcTag)
    {
        $nfcTag->delete();

        return redirect()->route('nfc.tags.index')
            ->with('success', 'Kartu NFC berhasil dihapus');
    }

    /**
     * Display the NFC logs (Admin).
     */
    public function indexLogs(Request $request)
    {
        $query = AbsensiNfcLog::with(['nfcTag', 'user', 'vendor'])
            ->orderBy('scanned_at', 'desc');

        // Filter by vendor
        if ($request->has('vendor_id') && $request->vendor_id) {
            $query->byVendor($request->vendor_id);
        }

        // Filter by log type
        if ($request->has('tipe_log') && $request->tipe_log) {
            $query->byType($request->tipe_log);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $endDate = $request->has('end_date') && $request->end_date
                ? $request->end_date
                : now()->format('Y-m-d');
            $query->byDateRange($request->start_date, $endDate);
        }

        $logs = $query->paginate(50);
        $vendors = Vendor::all();

        return view('nfc.logs.index', compact('logs', 'vendors'));
    }

    /**
     * Export NFC logs to CSV (Optional).
     */
    public function exportLogs(Request $request)
    {
        $query = AbsensiNfcLog::with(['nfcTag', 'user', 'vendor']);

        // Apply same filters as indexLogs
        if ($request->has('vendor_id') && $request->vendor_id) {
            $query->byVendor($request->vendor_id);
        }

        if ($request->has('tipe_log') && $request->tipe_log) {
            $query->byType($request->tipe_log);
        }

        if ($request->has('start_date') && $request->start_date) {
            $endDate = $request->has('end_date') && $request->end_date
                ? $request->end_date
                : now()->format('Y-m-d');
            $query->byDateRange($request->start_date, $endDate);
        }

        $logs = $query->orderBy('scanned_at', 'desc')->get();

        $filename = 'absensi_nfc_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, [
                'ID',
                'Serial Number',
                'Nama Pemilik',
                'Tipe Log',
                'Waktu Scan',
                'Operator',
                'Vendor',
            ]);

            // CSV Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->nfcTag->serial_number ?? 'N/A',
                    $log->nfcTag->nama_pemilik ?? 'N/A',
                    $log->getTypeLabelAttribute(),
                    $log->getFormattedScannedAtAttribute(),
                    $log->user->name ?? 'N/A',
                    $log->vendor->nama_vendor ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
