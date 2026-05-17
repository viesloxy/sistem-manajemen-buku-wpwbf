<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AntrianAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:antrian_admin');
    }

    // =============================================================
    // 1. Dashboard Admin (/antrian/admin)
    // =============================================================
    public function index()
    {
        $stats = [
            'menunggu'  => Antrian::hariIni()->menunggu()->count(),
            'dipanggil' => Antrian::hariIni()->dipanggil()->count(),
            'terlambat' => Antrian::hariIni()->terlambat()->count(),
            'selesai'   => Antrian::hariIni()->selesai()->count(),
        ];

        $dipanggil = Antrian::hariIni()->dipanggil()
                              ->with('vendor')
                              ->latest('dipanggil_pada')
                              ->first();

        $antrians = Antrian::hariIni()
                           ->with('vendor')
                           ->orderBy('id', 'asc')
                           ->get();

        $vendors = Vendor::all();

        return view('antrian.admin.index', compact(
            'stats', 'dipanggil', 'antrians', 'vendors'
        ));
    }

    // =============================================================
    // 2. Panggil Nomor Berikutnya
    // =============================================================
    public function panggilBerikutnya(Request $request)
    {
        // 1) Tandai semua "dipanggil" sebelumnya → "terlambat"
        Antrian::hariIni()->dipanggil()->update(['status' => 'terlambat']);

        // 2) Ambil antrian berikutnya yang masih menunggu
        $next = Antrian::hariIni()->menunggu()->urut()->first();

        if (!$next) {
            return back()->with('error', 'Tidak ada antrian yang menunggu.');
        }

        // 3) Update jadi "dipanggil"
        $next->update([
            'status'         => 'dipanggil',
            'dipanggil_pada' => now(),
            'user_id'        => Auth::id(),
        ]);

        $this->updateSseCache();

        return back()->with('success', "Nomor {$next->nomor} ({$next->nama}) dipanggil.");
    }

    // =============================================================
    // 3. Selesaikan Pemanggilan (status → selesai)
    // =============================================================
    public function selesaikan(Antrian $antrian)
    {
        if ($antrian->status === 'selesai') {
            return back()->with('error', 'Antrian ini sudah selesai.');
        }

        $antrian->update(['status' => 'selesai']);
        $this->updateSseCache();

        return back()->with('success', "Nomor {$antrian->nomor} ditandai selesai.");
    }

    // =============================================================
    // 4. Tandai Terlambat (status → terlambat)
    // =============================================================
    public function tandaiTerlambat(Antrian $antrian)
    {
        if ($antrian->status !== 'dipanggil') {
            return back()->with('error', 'Hanya antrian yang sedang dipanggil yang bisa ditandai terlambat.');
        }

        $antrian->update([
            'status'             => 'terlambat',
            'keterlambatan_pada' => now(),
        ]);

        $this->updateSseCache();

        return back()->with('success', "Nomor {$antrian->nomor} ditandai terlambat.");
    }

    // =============================================================
    // 5. Panggil Ulang Nomor Terlambat
    // =============================================================
    public function panggilUlang(Antrian $antrian)
    {
        if (!in_array($antrian->status, ['terlambat', 'menunggu'])) {
            return back()->with('error', 'Nomor ini tidak bisa dipanggil ulang.');
        }

        Antrian::hariIni()->dipanggil()->update(['status' => 'terlambat']);

        $antrian->update([
            'status'             => 'dipanggil',
            'dipanggil_pada'     => now(),
            'keterlambatan_pada' => null,
            'user_id'            => Auth::id(),
        ]);

        $this->updateSseCache();

        return back()->with('success', "Nomor {$antrian->nomor} ({$antrian->nama}) dipanggil ulang.");
    }

    // =============================================================
    // 6. Reset Semua Antrian Hari Ini
    // =============================================================
    public function resetHariIni(Request $request)
    {
        $count = Antrian::hariIni()->count();

        if ($count === 0) {
            return back()->with('error', 'Tidak ada antrian hari ini.');
        }

        Antrian::hariIni()->delete();
        $this->updateSseCache();

        return back()->with('success', "{$count} antrian hari ini telah direset.");
    }

    // =============================================================
    // Helper: Update Cache SSE
    // =============================================================
    private function updateSseCache()
    {
        $dipanggil = Antrian::hariIni()->dipanggil()
                             ->with('vendor')
                             ->latest('dipanggil_pada')
                             ->first();

        $data = [
            'dipanggil' => $dipanggil ? [
                'id'     => $dipanggil->id,
                'nomor'  => $dipanggil->nomor,
                'nama'   => $dipanggil->nama,
                'vendor' => $dipanggil->vendor?->nama_vendor,
            ] : null,
            'stats' => [
                'menunggu'  => Antrian::hariIni()->menunggu()->count(),
                'dipanggil' => Antrian::hariIni()->dipanggil()->count(),
                'terlambat' => Antrian::hariIni()->terlambat()->count(),
                'selesai'   => Antrian::hariIni()->selesai()->count(),
            ],
            'menunggu' => Antrian::hariIni()->menunggu()
                               ->with('vendor')
                               ->urut()
                               ->get(['id', 'nomor', 'nama', 'vendor_id'])
                               ->map(fn($a) => [
                                   'id'     => $a->id,
                                   'nomor'  => $a->nomor,
                                   'nama'   => $a->nama,
                                   'vendor' => $a->vendor?->nama_vendor,
                               ]),
        ];

        Cache::put('antrian_data', $data, now()->addDay());
    }
}