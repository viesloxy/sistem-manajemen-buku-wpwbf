<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::orderBy('timestamp', 'desc')->get();
        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        // Mengarahkan ke file form.blade.php
        return view('barang.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
        ]);

        Barang::create($request->all());
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        // Mengarahkan ke file form.blade.php dengan membawa data barang
        return view('barang.form', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function cetakLabel(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'start_x' => 'required|integer|min:1|max:5',
            'start_y' => 'required|integer|min:1|max:8',
        ]);

        $barangData = Barang::whereIn('id_barang', $request->ids)->get();
        $startIndex = (($request->start_y - 1) * 5) + ($request->start_x - 1);
        
        $labels = array_fill(0, 40, null);
        $currentIndex = $startIndex;
        foreach ($barangData as $item) {
            if ($currentIndex < 40) {
                $labels[$currentIndex] = $item;
                $currentIndex++;
            }
        }

        $rows = array_chunk($labels, 5);
        $pdf = Pdf::loadView('barang.pdf_label', compact('rows'));
        $pdf->setPaper([0, 0, 595.28, 481.89], 'portrait');

        return $pdf->stream('Label_TnJ108.pdf');
    }
}