<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function generateKatalog()
    {
        $dataBuku = Buku::with('kategori')->get();

        $pdf = Pdf::loadView('laporan.pdf_katalog', compact('dataBuku'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream('Katalog_Buku_Eksklusif.pdf');
    }

    public function generateStok()
    {
        $dataBuku = Buku::with('kategori')->get();

        $pdf = Pdf::loadView('laporan.pdf_stok', compact('dataBuku'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Stok_Buku.pdf');
    }
}