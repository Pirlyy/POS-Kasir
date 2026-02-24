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
     * SIMPAN TRANSAKSI POS
     */
    public function simpanTransaksi(Request $request)
    {
        try {

            $request->validate([
                'produk' => 'required|array|min:1',
                'total'  => 'required|numeric|min:1',
                'bayar'  => 'required|numeric|min:0',
            ]);

            $produk  = collect($request->produk);

            $subtotal = (float) $request->subtotal;
            $diskonTransaksi = (float) $request->diskon_transaksi;
            $pajak = (float) $request->pajak;
            $total = (float) $request->total;
            $bayar = (float) $request->bayar;
            $kembalian = $bayar - $total;

            if ($bayar < $total && $request->metode_pembayaran === 'cash') {
                return response()->json([
                    'message' => 'Bayar kurang'
                ], 422);
            }

            $pengeluaran = PengeluaranBarang::create([
                'nomor_pengeluaran' => PengeluaranBarang::nomerpengeluaran(),
                'nama_petugas' => Auth::user()->name ?? 'Kasir',
                'subtotal' => $subtotal,
                'diskon_transaksi' => $diskonTransaksi,
                'pajak' => $pajak,
                'total_harga' => $total,
                'bayar' => $bayar,
                'kembalian' => $kembalian,
                'metode_pembayaran' => $request->metode_pembayaran,
            ]);

            foreach ($produk as $item) {

                $product = Product::findOrFail($item['produk_id']);

                $qty = (int) $item['qty'];
                $harga = (float) $item['harga'];
                $diskonPersen = (int) $item['diskon_persen'];

                $hargaAwal = $qty * $harga;
                $diskonNominal = ($hargaAwal * $diskonPersen) / 100;
                $subTotalFix = $hargaAwal - $diskonNominal;

                $pengeluaran->items()->create([
                    'product_id' => $product->id,
                    'jumlah' => $qty,
                    'harga_jual' => $harga,
                    'diskon_item' => $diskonNominal, // ✅ SESUAI DATABASE
                    'sub_total' => $subTotalFix,
                ]);

                $product->decrement('stok', $qty);
            }

            return response()->json([
                'id' => $pengeluaran->id
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
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