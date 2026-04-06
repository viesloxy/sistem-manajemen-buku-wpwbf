<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    // Menampilkan daftar semua user
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        
        return view('user.index', compact('users'));
    }

    // Mengubah role user (khususnya menjadi Vendor)
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,vendor,customer'
        ]);

        $user = User::findOrFail($id);
        $roleLama = $user->role;
        $roleBaru = $request->role;

        // Update role di tabel users
        $user->update(['role' => $roleBaru]);

        // LOGIKA PENTING: Jika diubah menjadi VENDOR
        if ($roleBaru === 'vendor' && $roleLama !== 'vendor') {
            // Cek apakah dia sudah punya data di tabel vendors
            $cekVendor = Vendor::where('user_id', $user->id)->first();
            
            // Jika belum punya, buatkan profil vendor otomatis
            if (!$cekVendor) {
                Vendor::create([
                    'user_id' => $user->id,
                    'nama_vendor' => 'Kantin ' . $user->name // Default nama kantin
                ]);
            }
        }

        // UBAH DI SINI: Redirect kembali ke route user.index yang baru
        return redirect()->route('user.index')->with('success', 'Role pengguna ' . $user->name . ' berhasil diubah menjadi ' . strtoupper($roleBaru));
    }
}