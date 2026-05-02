<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class ScannerBarangController extends Controller
{
    public function index()
    {
        return view('scanner.index');
    }

    public function cekBarang(Request $request)
    {
        $idBarang = $request->id_barang;
        $barang = Barang::where('id_barang', $idBarang)->first();

        if ($barang) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id_barang' => $barang->id_barang,
                    'nama' => $barang->nama,
                    'harga' => $barang->harga,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Barang tidak ditemukan'
        ], 404);
    }
}