<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerAccessController extends Controller
{
    /**
     * Display a listing of customers (Data Customer)
     */
    public function index()
    {
        $customers = Customer::orderBy('created_at', 'desc')->get();
        return view('customer-data.index', compact('customers'));
    }

    /**
     * Show form to add customer with blob photo (Tambah Customer 1)
     */
    public function createBlob()
    {
        return view('customer-data.create-blob');
    }

    /**
     * Store customer with blob photo
     */
    public function storeBlob(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|string',
        ]);

        Customer::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'foto_blob' => $request->foto,
            'foto_path' => null,
        ]);

        return redirect()->route('customer-data.index')
            ->with('success', 'Customer dengan foto blob berhasil ditambahkan!');
    }

    /**
     * Show form to add customer with file photo (Tambah Customer 2)
     */
    public function createFile()
    {
        return view('customer-data.create-file');
    }

    /**
     * Store customer with file photo
     */
    public function storeFile(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'foto_blob' => null,
        ];

        // Simpan file foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = 'customer_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/customers', $filename, 'public');
            $data['foto_path'] = 'storage/' . $path;
        }

        Customer::create($data);

        return redirect()->route('customer-data.index')
            ->with('success', 'Customer dengan foto file berhasil ditambahkan!');
    }

    /**
     * Display the specified customer
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer-data.show', compact('customer'));
    }

    /**
     * Remove the specified customer
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        // Hapus file jika ada
        if ($customer->foto_path && file_exists(public_path($customer->foto_path))) {
            unlink(public_path($customer->foto_path));
        }

        $customer->delete();

        return redirect()->route('customer-data.index')
            ->with('success', 'Customer berhasil dihapus!');
    }
}
