<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranBarang;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranBarangController extends Controller
{
    /**
     * HALAMAN TRANSAKSI (ADMIN)
     */
    public function index()
    {
        return view('pengeluaran-barang.index');
    }

    /**
     * SIMPAN TRANSAKSI (ADMIN & KASIR AJAX)
     */
    public function store(Request $request)
    {
        $request->validate([
            'produk' => 'required|array|min:1',
            'bayar'  => 'required|numeric|min:1',
            'metode_pembayaran' => 'required',
        ]);

        $produk = collect($request->produk);

        $subtotal = 0;        // total sebelum diskon transaksi
        $totalDiskonItem = 0; // total diskon per produk

        /**
         * ===============================
         * HITUNG SUBTOTAL + DISKON ATAS
         * ===============================
         */
        foreach ($produk as $item) {

            $product = Product::findOrFail($item['produk_id']);

            $qty   = (int) $item['qty'];
            $harga = $product->harga_jual;

            // diskon per produk (diskon atas)
            $diskonItem = isset($item['diskon_item'])
                ? (int) $item['diskon_item']
                : 0;

            $subTotalItem = ($qty * $harga) - $diskonItem;

            if ($subTotalItem < 0) {
                $subTotalItem = 0;
            }

            $subtotal += $subTotalItem;
            $totalDiskonItem += $diskonItem;
        }

        /**
         * ===============================
         * DISKON BAWAH (TOTAL TRANSAKSI)
         * ===============================
         */
        $diskonTransaksi = (int) ($request->diskon_transaksi ?? 0);

        $totalSetelahDiskon = $subtotal - $diskonTransaksi;

        if ($totalSetelahDiskon < 0) {
            $totalSetelahDiskon = 0;
        }

        /**
         * ===============================
         * PAJAK (DEFAULT 11%)
         * ===============================
         */
        $pajakRate = 0.11;
        $pajak = $totalSetelahDiskon * $pajakRate;

        /**
         * ===============================
         * GRAND TOTAL
         * ===============================
         */
        $grandTotal = $totalSetelahDiskon + $pajak;

        /**
         * ===============================
         * VALIDASI PEMBAYARAN
         * ===============================
         */
        $bayar = (int) $request->bayar;

        if ($bayar < $grandTotal) {
            return response()->json([
                'message' => 'Bayar kurang'
            ], 422);
        }

        /**
         * ===============================
         * SIMPAN HEADER TRANSAKSI
         * ===============================
         */
        $pengeluaran = PengeluaranBarang::create([
            'nomor_pengeluaran' => PengeluaranBarang::nomorPengeluaran(),
            'nama_petugas'      => Auth::user()->name,
            'metode_pembayaran' => $request->metode_pembayaran,

            'subtotal'          => $subtotal,
            'diskon_item'       => $totalDiskonItem,
            'diskon_transaksi'  => $diskonTransaksi,
            'pajak'             => $pajak,
            'total_harga'       => $grandTotal,

            'bayar'             => $bayar,
            'kembalian'         => $bayar - $grandTotal,
        ]);

        /**
         * ===============================
         * SIMPAN DETAIL ITEM + UPDATE STOK
         * ===============================
         */
        foreach ($produk as $item) {

            $product = Product::findOrFail($item['produk_id']);

            $qty   = (int) $item['qty'];
            $harga = $product->harga_jual;
            $diskonItem = isset($item['diskon_item'])
                ? (int) $item['diskon_item']
                : 0;

            $subTotalItem = ($qty * $harga) - $diskonItem;

            if ($subTotalItem < 0) {
                $subTotalItem = 0;
            }

            $pengeluaran->items()->create([
                'product_id'  => $product->id,
                'jumlah'      => $qty,
                'harga_jual'  => $harga,
                'diskon_item' => $diskonItem,
                'sub_total'   => $subTotalItem,
            ]);

            // update stok
            $product->decrement('stok', $qty);
        }

        return response()->json([
            'id' => $pengeluaran->id
        ]);
    }

    /**
     * LAPORAN ADMIN (LIST)
     */
    public function laporan()
    {
        $data = PengeluaranBarang::orderBy('created_at', 'desc')->get();
        return view('pengeluaran-barang.laporan', compact('data'));
    }

    /**
     * DETAIL LAPORAN ADMIN
     */
    public function detailLaporan($nomor_pengeluaran)
    {
        $pengeluaran = PengeluaranBarang::with('items.product')
            ->where('nomor_pengeluaran', $nomor_pengeluaran)
            ->firstOrFail();

        return view('pengeluaran-barang.detail', compact('pengeluaran'));
    }

    /**
     * PRINT STRUK (ADMIN & KASIR)
     */
    public function print($id)
    {
        $pengeluaran = PengeluaranBarang::with('items.product')
            ->findOrFail($id);

        return view('pengeluaran-barang.print', compact('pengeluaran'));
    }
}
