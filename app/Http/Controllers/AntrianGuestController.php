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
            'vendor_id' => 'required|exists:vendors,id',  // WAJIB pilih vendor
        ]);

        // Generate nomor antrian berikutnya — berdasarkan semua record (bukan hanya hari ini)
        // Agar nomor tetap unik secara global, tidak bentrok dengan hari sebelumnya
        $lastAntrian = Antrian::max('nomor');
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

        // Redirect ke halaman sukses dengan action buttons
        return redirect()->route('antrian.sukses', $antrian->id);
    }

    // =============================================================
    // 4. Halaman Sukses Pendaftaran (/antrian/sukses/{id})
    // =============================================================
    public function sukses($id)
    {
        $antrian = Antrian::with('vendor')->findOrFail($id);
        return view('antrian.sukses', compact('antrian'));
    }

    // =============================================================
    // 5. Cetak PDF Nomor Antrian (/antrian/cetak-pdf/{id})
    // =============================================================
    public function cetakPdf($id)
    {
        $antrian = Antrian::with('vendor')->findOrFail($id);

        // Generate PDF menggunakan DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('antrian.pdf', compact('antrian'));
        $pdf->setPaper('A5', 'portrait');

        return $pdf->download('nomor-antrian-' . $antrian->nomor . '.pdf');
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
            // TAMBAHAN: Daftar SEMUA antrian hari ini untuk update tabel admin
            'antrians' => Antrian::hariIni()
                               ->with('vendor')
                               ->orderBy('nomor', 'asc')
                               ->get()
                               ->map(fn($a) => [
                                   'id'         => $a->id,
                                   'nomor'      => $a->nomor,
                                   'nama'       => $a->nama,
                                   'vendor'     => $a->vendor?->nama_vendor,
                                   'status'     => $a->status,
                                   'created_at' => $a->created_at->format('H:i'),
                               ]),
        ];

        Cache::put('antrian_data', $data, now()->addDay());
    }
}