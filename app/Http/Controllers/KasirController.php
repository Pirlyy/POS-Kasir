<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

// MIDTRANS
use Midtrans\Config;
use Midtrans\Snap;

class KasirController extends Controller
{
    /**
     * Halaman Kasir (POS)
     */
    public function index()
    {
        $products = Product::where('is_active', 1)
            ->where('stok', '>', 0)
            ->get();

        return view('kasir.index', compact('products'));
    }

    /**
     * Generate Snap Token Midtrans (QRIS / E-Wallet)
     */
    public function midtransToken(Request $request)
    {
        // VALIDASI
        $request->validate([
            'total' => 'required|numeric|min:1',
        ]);

        // CONFIG MIDTRANS (SANDBOX)
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        // PARAM TRANSAKSI
        $params = [
            'transaction_details' => [
                'order_id'      => 'POS-' . now()->format('YmdHis') . '-' . rand(100,999),
                'gross_amount' => (int) $request->total,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name ?? 'Customer',
            ],
            'enabled_payments' => ['qris'], // 🔥 QRIS ONLY
        ];

        // GENERATE TOKEN
        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'token' => $snapToken
        ]);
    }
}
