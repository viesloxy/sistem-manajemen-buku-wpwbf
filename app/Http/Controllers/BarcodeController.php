<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    /**
     * Display a listing of barcodes (API).
     */
    public function index()
    {
        return response()->json(Barcode::all());
    }

    /**
     * Show barcode by code (API).
     */
    public function show($barcode)
    {
        $data = Barcode::where('barcode', $barcode)->first();

        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Barcode tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * Display barcode list page (Admin).
     */
    public function adminIndex()
    {
        $title = 'Daftar Barcode';
        $barcodes = Barcode::orderBy('created_at', 'desc')->paginate(10);
        return view('geolocation.barcode.index', compact('title', 'barcodes'));
    }

    /**
     * Show form for creating barcode (Admin).
     */
    public function create()
    {
        $title = 'Tambah Barcode';
        return view('geolocation.barcode.create', compact('title'));
    }

    /**
     * Store new barcode (Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode'    => 'required|unique:barcodes,barcode',
            'nama_toko'  => 'required|string|max:255',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
            'accuracy'   => 'nullable|numeric',
        ]);

        $validated['accuracy'] = $validated['accuracy'] ?? 50;

        Barcode::create($validated);

        return redirect()->route('geolocation.barcode.index')
                         ->with('success', 'Barcode berhasil ditambahkan!');
    }

    /**
     * Show form for editing barcode (Admin).
     */
    public function edit($id)
    {
        $title = 'Edit Barcode';
        $barcode = Barcode::findOrFail($id);
        return view('geolocation.barcode.edit', compact('title', 'barcode'));
    }

    /**
     * Update barcode (Admin).
     */
    public function update(Request $request, $id)
    {
        $barcode = Barcode::findOrFail($id);

        $validated = $request->validate([
            'barcode'    => 'required|unique:barcodes,barcode,' . $id,
            'nama_toko'  => 'required|string|max:255',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
            'accuracy'   => 'nullable|numeric',
        ]);

        $validated['accuracy'] = $validated['accuracy'] ?? 50;

        $barcode->update($validated);

        return redirect()->route('geolocation.barcode.index')
                         ->with('success', 'Barcode berhasil diperbarui!');
    }

    /**
     * Delete barcode (Admin).
     */
    public function destroy($id)
    {
        $barcode = Barcode::findOrFail($id);
        $barcode->delete();

        return redirect()->route('geolocation.barcode.index')
                         ->with('success', 'Barcode berhasil dihapus!');
    }

    /**
     * Print barcode (Admin).
     */
    public function print($id)
    {
        $barcode = Barcode::findOrFail($id);
        return view('geolocation.barcode.print', compact('barcode'));
    }
}