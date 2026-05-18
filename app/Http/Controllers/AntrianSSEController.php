<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AntrianSSEController extends Controller
{
    public function stream(Request $request)
    {
        // Ambil data terbaru
        $data = Cache::get('antrian_data', [
            'dipanggil' => null,
            'stats' => ['menunggu' => 0, 'dipanggil' => 0, 'terlambat' => 0, 'selesai' => 0],
            'menunggu' => [],
        ]);

        // Kirim SATU event, lalu koneksi langsung ditutup
        // Browser EventSource otomatis reconnect
        return response()->stream(function () use ($data) {
            echo 'event: queue-update' . PHP_EOL;
            echo 'data: ' . json_encode($data) . PHP_EOL;
            echo PHP_EOL;

            ob_flush();
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}