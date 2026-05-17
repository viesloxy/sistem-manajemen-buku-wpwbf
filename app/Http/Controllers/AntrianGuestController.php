<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AntrianGuestController extends Controller
{
    // =============================================================
    // 1. Halaman Pendaftaran Antrian (/antrian/guest)
    // =============================================================
    public function guest()
    {
        $vendors = Vendor::all();  // Ambil semua vendor untuk dropdown
        return view('antrian.guest', compact('vendors'));
    }

    // =============================================================
    // 2. Proses Pendaftaran (/antrian/daftar)
    // =============================================================
    public function daftar(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);

        // Generate nomor antrian berikutnya
        $lastAntrian = Antrian::whereDate('created_at', today())->max('nomor');
        $lastNumber   = $lastAntrian ? (int) $lastAntrian : 0;
        $nomorBaru    = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        // Simpan ke database
        $antrian = Antrian::create([
            'nomor'     => $nomorBaru,
            'nama'      => $request->nama,
            'vendor_id' => $request->vendor_id,
            'status'    => 'menunggu',
        ]);

        // Update cache SSE (agar semua client langsung tahu ada antrian baru)
        $this->updateSseCache();

        // Redirect ke tab monitoring pribadi
        return redirect()->route('antrian.saya', $antrian->id);
    }

    // =============================================================
    // 3. Halaman Monitoring Pribadi (/antrian/saya/{id})
    // =============================================================
    public function saya($id)
    {
        $antrian = Antrian::with('vendor')->findOrFail($id);

        // Hitung posisi antrian
        $posisi = Antrian::where('status', 'menunggu')
            ->where('nomor', '<', $antrian->nomor)
            ->count() + 1;

        return view('antrian.saya', compact('antrian', 'posisi'));
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
                'id'       => $dipanggil->id,
                'nomor'    => $dipanggil->nomor,
                'nama'     => $dipanggil->nama,
                'vendor'   => $dipanggil->vendor?->nama_vendor,
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
                                   'id'    => $a->id,
                                   'nomor' => $a->nomor,
                                   'nama'  => $a->nama,
                                   'vendor'=> $a->vendor?->nama_vendor,
                               ]),
        ];

        Cache::put('antrian_data', $data, now()->addDay());
    }
}