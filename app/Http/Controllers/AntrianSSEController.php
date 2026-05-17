<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AntrianSSEController extends Controller
{
    public function stream(Request $request)
    {
        return response()->stream(function () {
            set_time_limit(0);

            while (true) {
                $data = Cache::get('antrian_data', [
                    'dipanggil' => null,
                    'stats'    => ['menunggu' => 0, 'dipanggil' => 0, 'terlambat' => 0, 'selesai' => 0],
                    'menunggu' => [],
                ]);

                echo 'event: queue-update' . PHP_EOL;
                echo 'data: ' . json_encode($data) . PHP_EOL;
                echo PHP_EOL;

                ob_flush();
                flush();

                if (connection_aborted()) {
                    break;
                }

                usleep(1000000); // 1 detik
            }
        }, 200, [
            'Content-Type'  => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}