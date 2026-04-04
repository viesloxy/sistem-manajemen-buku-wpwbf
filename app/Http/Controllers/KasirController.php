<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index() {
        return view('kasir.index');
    }

    public function cekBarang($kode) {
        $kodeSearch = trim($kode);

        $barang = Barang::where('id_barang', $kodeSearch)->first();

        if (!$barang) {
            $barang = Barang::all()->firstWhere('id_barang', $kodeSearch);
        }

        if ($barang) {
            return response()->json([
                'status' => 'success', 
                'data' => $barang
            ]);
        }
        
        return response()->json(['status' => 'error'], 404);
    }

    public function store(Request $request) {
        DB::beginTransaction();
        try {
            $idPenjualan = DB::table('penjualan')->insertGetId([
                'total' => $request->total,
                'created_at' => now(),
                'updated_at' => now()
            ], 'id_penjualan');

            foreach ($request->items as $item) {
                DB::table('penjualan_detail')->insert([
                    'id_penjualan' => $idPenjualan, 
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}