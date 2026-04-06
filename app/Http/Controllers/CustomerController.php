<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    // 1. Menampilkan Halaman POS Customer
    public function index()
    {
        // Ambil semua vendor untuk dropdown pertama
        $vendors = Vendor::all();
        return view('customer.pos', compact('vendors'));
    }

    // 2. API untuk Select Berjenjang (Ambil Menu berdasarkan Vendor)
    public function getMenuByVendor($vendor_id)
    {
        $menus = Menu::where('vendor_id', $vendor_id)->get();
        return response()->json(['data' => $menus]);
    }

    // 3. API untuk mengambil detail 1 Menu saat di-klik
    public function getMenuDetail($menu_id)
    {
        $menu = Menu::find($menu_id);
        if ($menu) {
            return response()->json(['status' => 'success', 'data' => $menu]);
        }
        return response()->json(['status' => 'error'], 404);
    }

    // 4. Proses Checkout dan Panggil Midtrans
    public function checkout(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric',
            'items' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            // A. BUAT USER GUEST OTOMATIS
            // Cari Guest terakhir untuk membuat angka increment (Guest_0000001)
            $lastGuest = User::where('name', 'like', 'Guest_%')->orderBy('id', 'desc')->first();
            $nextNumber = 1;
            if ($lastGuest) {
                $lastNumber = (int) str_replace('Guest_', '', $lastGuest->name);
                $nextNumber = $lastNumber + 1;
            }
            $guestName = 'Guest_' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

            // Simpan User Guest ke database (tanpa email asli/password)
            $user = User::create([
                'name' => $guestName,
                'email' => strtolower($guestName) . '@guest.com', // Email dummy
                'password' => bcrypt(Str::random(10)),
                'role' => 'customer'
            ]);

            // B. BUAT DATA PESANAN
            $pesanan = Pesanan::create([
                'nama' => $guestName,
                'total' => $request->total,
                'status_bayar' => 'Pending',
            ]);

            // C. SIMPAN DETAIL PESANAN
            foreach ($request->items as $item) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'menu_id' => $item['id_menu'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            // D. KONFIGURASI MIDTRANS
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
            \Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS', true);

            // Parameter untuk dikirim ke Midtrans
            $params = array(
                'transaction_details' => array(
                    'order_id' => 'ORD-' . $pesanan->id . '-' . time(), // ID unik
                    'gross_amount' => $request->total,
                ),
                'customer_details' => array(
                    'first_name' => $guestName,
                    'email' => $user->email,
                ),
            );

            // Dapatkan Snap Token dari Midtrans
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Simpan snap token ke pesanan
            $pesanan->update(['snap_token' => $snapToken]);

            DB::commit();

            // Kirim token ke frontend
            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
                'pesanan_id' => $pesanan->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // 5. Webhook/Callback dari Midtrans (Otomatis update status jadi Lunas)
    public function midtransCallback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                // Ekstrak ID Pesanan asli (Keluarkan dari ORD-1-123456)
                $orderIdParts = explode('-', $request->order_id);
                $asliId = $orderIdParts[1];

                $pesanan = Pesanan::find($asliId);
                if ($pesanan) {
                    $pesanan->update([
                        'status_bayar' => 'Lunas',
                        'metode_bayar' => $request->payment_type
                    ]);
                }
            }
        }
        return response()->json(['status' => 'success']);
    }
}