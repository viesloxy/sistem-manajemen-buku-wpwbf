<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class VendorMenuController extends Controller
{
    public function index()
    {
        // Otomatis membuat profil Vendor jika belum punya
        $vendor = Vendor::firstOrCreate(
            ['user_id' => Auth::id()],
            ['nama_vendor' => 'Toko ' . Auth::user()->name]
        );

        // Ambil data menu khusus milik vendor yang sedang login
        $menus = Menu::where('vendor_id', $vendor->id)->orderBy('id', 'desc')->get();

        return view('vendor.menu.index', compact('vendor', 'menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $vendor = Vendor::where('user_id', Auth::id())->first();
        $path_gambar = null;

        // Proses Upload Gambar
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            // Simpan ke folder public/uploads/menus
            $file->move(public_path('uploads/menus'), $nama_file);
            $path_gambar = 'uploads/menus/' . $nama_file;
        }

        Menu::create([
            'vendor_id' => $vendor->id,
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga,
            'path_gambar' => $path_gambar,
        ]);

        return redirect()->route('vendor.menu.index')->with('success', 'Menu/Produk berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        // Hapus gambar fisik jika ada
        if ($menu->path_gambar && File::exists(public_path($menu->path_gambar))) {
            File::delete(public_path($menu->path_gambar));
        }

        $menu->delete();

        return redirect()->route('vendor.menu.index')->with('success', 'Menu/Produk berhasil dihapus!');
    }
}