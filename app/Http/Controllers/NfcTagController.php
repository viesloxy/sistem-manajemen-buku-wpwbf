<?php

namespace App\Http\Controllers;

use App\Models\NfcTag;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;

class NfcTagController extends Controller
{
    public function index()
    {
        $tags = NfcTag::with(['user', 'vendor'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $vendors = Vendor::all();

        return view('nfc.tags.index', compact('tags', 'vendors'));
    }

    public function create()
    {
        $users = User::where('role', '!=', 'customer')->get();
        $vendors = Vendor::all();

        return view('nfc.tags.create', compact('users', 'vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'serial_number' => 'required|string|unique:nfc_tags,serial_number',
            'user_id' => 'nullable|exists:users,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'nama_pemilik' => 'required|string|max:255',
            'tipe' => 'required|in:staff,vendor,admin',
            'status' => 'required|in:aktif,nonaktif,hilang',
        ]);

        NfcTag::create($validated);

        return redirect()->route('nfc.tags.index')
            ->with('success', 'Kartu NFC berhasil ditambahkan');
    }

    public function edit(NfcTag $tag)
    {
        $users = User::where('role', '!=', 'customer')->get();
        $vendors = Vendor::all();

        return view('nfc.tags.edit', compact('tag', 'users', 'vendors'));
    }

    public function update(Request $request, NfcTag $tag)
    {
        $validated = $request->validate([
            'serial_number' => 'required|string|unique:nfc_tags,serial_number,' . $tag->id,
            'user_id' => 'nullable|exists:users,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'nama_pemilik' => 'required|string|max:255',
            'tipe' => 'required|in:staff,vendor,admin',
            'status' => 'required|in:aktif,nonaktif,hilang',
        ]);

        $tag->update($validated);

        return redirect()->route('nfc.tags.index')
            ->with('success', 'Kartu NFC berhasil diperbarui');
    }

    public function destroy(NfcTag $tag)
    {
        $tag->delete();

        return redirect()->route('nfc.tags.index')
            ->with('success', 'Kartu NFC berhasil dihapus');
    }
}
