<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Support\Facades\Cache;

class AntrianPapanController extends Controller
{
    public function index()
    {
        $dipanggil = Antrian::hariIni()
                             ->dipanggil()
                             ->with('vendor')
                             ->latest('dipanggil_pada')
                             ->first();

        $menunggu = Antrian::hariIni()
                            ->menunggu()
                            ->with('vendor')
                            ->urut()
                            ->limit(6)
                            ->get();

        $stats = [
            'menunggu'  => Antrian::hariIni()->menunggu()->count(),
            'dipanggil' => Antrian::hariIni()->dipanggil()->count(),
            'terlambat' => Antrian::hariIni()->terlambat()->count(),
            'selesai'   => Antrian::hariIni()->selesai()->count(),
        ];

        return view('antrian.papan.index', compact(
            'dipanggil', 'menunggu', 'stats'
        ));
    }
}