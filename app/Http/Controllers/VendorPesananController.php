<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorPesananController extends Controller
{
    public function index(Request $request)
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return redirect()->route('vendor.menu.index')->with('success', 'Silakan tambahkan menu pertama Anda.');
        }

        // Ambil input filter
        $statusFilter = $request->input('status', 'Semua'); // Default tampilkan Semua
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Query Dasar (Hanya pesanan yang memuat menu vendor ini)
        $query = Pesanan::whereHas('detailPesanans.menu', function ($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })->with(['detailPesanans' => function ($q) use ($vendor) {
            $q->whereHas('menu', function ($q2) use ($vendor) {
                $q2->where('vendor_id', $vendor->id);
            })->with('menu');
        }]);

        // 1. Terapkan Filter Status
        if ($statusFilter !== 'Semua') {
            $query->where('status_bayar', $statusFilter);
        }

        // 2. Terapkan Filter Tanggal
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $pesanans = $query->orderBy('created_at', 'desc')->get();

        return view('vendor.pesanan.index', compact('pesanans', 'vendor', 'statusFilter', 'startDate', 'endDate'));
    }
}