<?php

namespace App\Http\Controllers;

use App\Models\AbsensiNfcLog;
use App\Models\Vendor;
use Illuminate\Http\Request;

class NfcLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AbsensiNfcLog::with(['nfcTag', 'user', 'vendor'])
            ->orderBy('scanned_at', 'desc');

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

        $logs = $query->paginate(50);
        $vendors = Vendor::all();

        return view('nfc.logs.index', compact('logs', 'vendors'));
    }
}
