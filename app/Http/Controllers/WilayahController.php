<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    public function index() {
        return view('wilayah.index');
    }

    // Mengambil data Provinsi
    public function getProvinsi() {
        $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        return response()->json($response->json());
    }

    // Mengambil data Kabupaten berdasarkan ID Provinsi
    public function getKabupaten($id) {
        $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$id}.json");
        return response()->json($response->json());
    }

    // Mengambil data Kecamatan berdasarkan ID Kabupaten
    public function getKecamatan($id) {
        $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$id}.json");
        return response()->json($response->json());
    }

    // Mengambil data Kelurahan berdasarkan ID Kecamatan
    public function getKelurahan($id) {
        $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$id}.json");
        return response()->json($response->json());
    }
}