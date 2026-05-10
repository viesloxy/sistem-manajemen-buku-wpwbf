<?php

namespace App\Http\Controllers;

use App\Models\Geolocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GeolocationController extends Controller
{
    // =====================
    // ADMIN METHODS
    // =====================

    public function map()
    {
        $title = 'Peta Lokasi';
        $vendors = User::where('role', 'vendor')->get();
        return view('geolocation.map', compact('title', 'vendors'));
    }

    public function list(Request $request)
    {
        $title = 'Daftar Lokasi';
        $vendors = User::where('role', 'vendor')->get();

        $query = Geolocation::with('user')->orderBy('created_at', 'desc');

        if ($request->vendor_id) {
            $query->where('user_id', $request->vendor_id);
        }
        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $geolocations = $query->paginate(10);

        return view('geolocation.list', compact('title', 'geolocations', 'vendors'));
    }

    public function create()
    {
        $title = 'Tambah Lokasi';
        return view('geolocation.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'address'   => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Geolocation::create([
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy'  => $request->accuracy ?? null,
            'address'   => $request->address,
            'type'      => 'titik_awal',
            'user_id'   => auth()->id(),
        ]);

        return redirect()->route('geolocation.list')
                         ->with('success', 'Lokasi berhasil disimpan!');
    }

    public function destroy($id)
    {
        $location = Geolocation::findOrFail($id);
        $location->delete();

        return redirect()->route('geolocation.list')
                         ->with('success', 'Lokasi berhasil dihapus!');
    }

    // =====================
    // VENDOR METHODS
    // =====================

    public function titikAwal()
    {
        $title = 'Input Titik Awal';
        return view('vendor.geolocation.titik-awal', compact('title'));
    }

    public function titikKunjungan()
    {
        $title = 'Titik Kunjungan';
        return view('vendor.geolocation.titik-kunjungan', compact('title'));
    }

    public function vendorStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'type'      => 'required|in:titik_awal,titik_kunjungan',
            'barcode_id'=> $request->type === 'titik_kunjungan' ? 'required' : 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = [
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy'  => $request->accuracy ?? null,
            'type'      => $request->type,
            'user_id'   => auth()->id(),
        ];

        // Data titik kunjungan
        if ($request->type === 'titik_kunjungan') {
            $barcode = \App\Models\Barcode::find($request->barcode_id);

            // Hitung jarak (Haversine)
            $distance = $this->calculateDistance(
                $request->latitude, $request->longitude,
                $barcode->latitude, $barcode->longitude
            );

            $data['barcode'] = $barcode->barcode;
            $data['status'] = $distance <= $barcode->accuracy ? 'diterima' : 'ditolak';
        }

        Geolocation::create($data);

        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan!']);
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $R = 6371000; // Radius bumi dalam meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $R * $c;
    }

    // =====================
    // API METHODS
    // =====================

    public function apiIndex()
    {
        return response()->json(Geolocation::with('user')->get());
    }

    public function apiIndexByVendor(Request $request)
    {
        $query = Geolocation::with('user');

        if ($request->vendor_id) {
            $query->where('user_id', $request->vendor_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        return response()->json($query->get());
    }

    public function apiStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'type'      => 'required|in:titik_awal,titik_kunjungan',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $data = [
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy'  => $request->accuracy ?? null,
            'type'      => $request->type,
            'user_id'   => $request->user_id ?? auth()->id(),
        ];

        if ($request->barcode) {
            $data['barcode'] = $request->barcode;
        }
        if ($request->status) {
            $data['status'] = $request->status;
        }

        $location = Geolocation::create($data);

        return response()->json(['success' => true, 'data' => $location], 201);
    }
}
