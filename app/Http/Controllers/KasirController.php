<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

// MIDTRANS
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    /**
     * Halaman Kasir (POS)
     */
   public function index(Request $request)
{
    $products = Product::where('is_active', 1)
        ->where('stok', '>', 0)
        ->when($request->q, function ($q) use ($request) {
            $q->where('nama_produk', 'like', '%'.$request->q.'%');
        })
        ->get();

    return view('kasir.index', compact('products'));
}


    /**
     * Generate Snap Token Midtrans (QRIS / E-Wallet)
     */
    public function midtransToken(Request $request)
{
    $request->validate([
        'total' => 'required|numeric|min:1',
    ]);

    // CONFIG MIDTRANS (BENAR)
    Config::$serverKey    = config('services.midtrans.server_key');
    Config::$isProduction = config('services.midtrans.is_production');
    Config::$isSanitized  = true;
    Config::$is3ds        = true;

    $params = [
        'transaction_details' => [
            'order_id'      => 'POS-' . time(),
            'gross_amount' => (int) $request->total,
        ],
        'customer_details' => [
            'first_name' => 'Customer',
        ],
        'enabled_payments' => ['qris'], // QRIS ONLY
    ];

    $snapToken = Snap::getSnapToken($params);

    return response()->json([
        'token' => $snapToken
    ]);
}


    public function checkout(Request $request)
{
    DB::beginTransaction();
    try {
        foreach ($request->items as $item) {

            // cek stok
            $product = Product::lockForUpdate()->find($item['id']);
            if ($product->stok < $item['qty']) {
                throw new \Exception('Stok tidak cukup');
            }

            // kurangi stok
            $product->decrement('stok', $item['qty']);
        }

        DB::commit();
        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 400);
    }
}



}
