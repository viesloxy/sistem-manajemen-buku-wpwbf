<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\User;
use Illuminate\Http\Request;

class VendorScanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('vendor.scan.index');
    }

    /**
     * Get order details for scanning result
     */
    public function getOrderDetail($id)
    {
        $pesanan = Pesanan::with(['detailPesanans.menu.vendor'])->find($id);

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        // Ambil semua menu dari pesanan
        $menus = [];
        $totalAmount = 0;

        foreach ($pesanan->detailPesanans as $detail) {
            if ($detail->menu && $detail->menu->vendor) {
                $menus[] = [
                    'nama' => $detail->menu->nama_menu,
                    'jumlah' => $detail->jumlah,
                    'harga' => $detail->harga,
                    'subtotal' => $detail->subtotal,
                    'vendor' => $detail->menu->vendor->nama_vendor
                ];
                $totalAmount += $detail->subtotal;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pesanan->id,
                'nama_pemesan' => $pesanan->nama,
                'total' => $pesanan->total,
                'status_bayar' => $pesanan->status_bayar,
                'metode_bayar' => $pesanan->metode_bayar ?? '-',
                'menus' => $menus,
                'total_amount' => $totalAmount
            ]
        ]);
    }
}