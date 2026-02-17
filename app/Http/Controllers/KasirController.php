<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PengeluaranBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// MIDTRANS
use Midtrans\Config;
use Midtrans\Snap;

class KasirController extends Controller
{
    /**
     * HALAMAN POS KASIR
     */
    public function index()
    {
        $products = Product::where('is_active', 1)
            ->where('stok', '>', 0)
            ->get();

        return view('kasir.index', compact('products'));
    }

    /**
     * SIMPAN TRANSAKSI POS (INI YANG PALING PENTING)
     */
    public function simpanTransaksi(Request $request)
    {
        $request->validate([
            'produk' => 'required|array|min:1',
            'bayar'  => 'required|numeric|min:1',
        ]);

        $produk = collect($request->produk);

        $total = $produk->sum('sub_total');
        $bayar = (int) $request->bayar;
        $kembalian = $bayar - $total;

        if ($bayar < $total) {
            return response()->json([
                'message' => 'Bayar kurang'
            ], 422);
        }

        /*
        ================================
        SIMPAN HEADER TRANSAKSI
        ================================
        */
        $pengeluaran = PengeluaranBarang::create([
            'nomor_pengeluaran' => PengeluaranBarang::nomerpengeluaran(),
            'nama_petugas'      => Auth::user()->name ?? 'Kasir',
            'bayar'             => $bayar,
            'kembalian'         => $kembalian,
            'total_harga'       => $total,
        ]);

        /*
        ================================
        SIMPAN DETAIL + UPDATE STOK
        ================================
        */
        foreach ($produk as $item) {

            $product = Product::findOrFail($item['produk_id']);

            $pengeluaran->items()->create([
                'product_id' => $product->id,
                'jumlah'     => $item['qty'],
                'harga_jual' => $product->harga_jual,
                'sub_total'  => $item['sub_total'],
            ]);

            // kurangi stok
            $product->decrement('stok', $item['qty']);
        }

        return response()->json([
            'id' => $pengeluaran->id
        ]);
    }

    /**
     * HALAMAN STRUK (PRINT)
     */
    public function struk($id)
    {
        $pengeluaran = PengeluaranBarang::with('items.product')
            ->findOrFail($id);

        return view('pengeluaran-barang.print', compact('pengeluaran'));
    }

    /**
     * MIDTRANS QRIS TOKEN
     */
    public function midtransToken(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric|min:1',
        ]);

        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'POS-' . now()->format('YmdHis') . rand(100,999),
                'gross_amount' => (int) $request->total,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name ?? 'Customer',
            ],
            'enabled_payments' => ['qris'],
        ];

        return response()->json([
            'token' => Snap::getSnapToken($params)
        ]);
    }
}
