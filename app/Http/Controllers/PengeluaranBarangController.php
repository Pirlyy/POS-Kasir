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
        if (empty($request->produk)) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Produk kosong'], 422)
                : redirect()->back()->withErrors('Produk kosong');
        }

        $request->validate([
            'produk' => 'required|array|min:1',
            'bayar'  => 'required|numeric|min:1',
        ]);

        $produk = collect($request->produk);
        $total  = $produk->sum('sub_total');
        $bayar  = (int) $request->bayar;
        $kembalian = $bayar - $total;

        if ($bayar < $total) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Bayar kurang'], 422)
                : redirect()->back()->withErrors('Bayar kurang');
        }

        // HEADER
        $pengeluaran = PengeluaranBarang::create([
            'nomor_pengeluaran' => PengeluaranBarang::nomerpengeluaran(),
            'nama_petugas'      => Auth::user()->name,
            'bayar'             => $bayar,
            'kembalian'         => $kembalian,
            'total_harga'       => $total,
        ]);

        // DETAIL + STOK
        foreach ($produk as $item) {
            $product = Product::findOrFail($item['produk_id']);

            $pengeluaran->items()->create([
                'product_id' => $product->id,
                'jumlah'     => $item['qty'],
                'harga_jual' => $product->harga_jual,
                'sub_total'  => $item['sub_total'],
            ]);

            $product->decrement('stok', $item['qty']);
        }

        // AJAX (KASIR)
        if ($request->expectsJson()) {
            return response()->json(['id' => $pengeluaran->id]);
        }

        // ADMIN
        return redirect()->route('pengeluaran-barang.print', $pengeluaran->id);
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
