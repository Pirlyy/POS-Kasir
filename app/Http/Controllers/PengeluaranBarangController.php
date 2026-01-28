<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranBarang;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PengeluaranBarangController extends Controller
{
    /**
     * Halaman transaksi pengeluaran barang
     */
    public function index()
    {
        return view('pengeluaran-barang.index');
    }

    /**
     * Cek harga & stok produk (AJAX)
     */
    public function cekharga(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id'
        ]);

        $product = Product::findOrFail($request->id);

        return response()->json([
            'harga_jual' => $product->harga_jual,
            'stok'       => $product->stok
        ]);
    }

    /**
     * Simpan transaksi pengeluaran barang
     */
    public function store(Request $request)
    {
        if (empty($request->produk)) {
            toast()->error('Data produk belum ditambahkan');
            return redirect()->back();
        }

        $request->validate([
            'produk' => 'required|array|min:1',
            'bayar'  => 'required|numeric|min:1',
        ], [
            'produk.required' => 'Produk harus diisi',
            'produk.min'      => 'Minimal 1 produk harus ditambahkan',
            'bayar.required'  => 'Jumlah bayar harus diisi',
            'bayar.numeric'   => 'Jumlah bayar harus berupa angka',
            'bayar.min'       => 'Jumlah bayar minimal 1'
        ]);

        $produk = collect($request->produk);
        $bayar  = $request->bayar;
        $total  = $produk->sum('sub_total');
        $kembalian = (int) $bayar - (int) $total;

        if ($bayar < $total) {
            toast()->error('Jumlah bayar tidak mencukupi');
            return redirect()->back()->withInput();
        }

        // SIMPAN HEADER TRANSAKSI
        $pengeluaran = PengeluaranBarang::create([
            'nomor_pengeluaran' => PengeluaranBarang::nomerpengeluaran(),
            'nama_petugas'      => Auth::user()->name,
            'bayar'             => $bayar,
            'kembalian'         => $kembalian,
            'total_harga'       => $total,
        ]);

        // SIMPAN DETAIL & KURANGI STOK
        foreach ($produk as $item) {
            $product = Product::findOrFail($item['produk_id']);

            $pengeluaran->items()->create([
                'product_id' => $product->id,
                'jumlah'        => $item['qty'],
                'harga_jual'      => $product->harga_jual,
                'sub_total'  => $item['sub_total'],
            ]);

            $product->decrement('stok', $item['qty']);
        }

        toast()->success('Data pengeluaran barang berhasil disimpan');

// redirect ke halaman print struk
return redirect()->route('pengeluaran-barang.print', $pengeluaran->id);
    }

    /**
     * LAPORAN PENGELUARAN BARANG
     * ➜ LANGSUNG TAMPIL DETAIL TRANSAKSI TERAKHIR
     */
    public function laporan()
    {
        $pengeluaran = PengeluaranBarang::with('items')
            ->latest()
            ->firstOrFail();

        return view('pengeluaran-barang.detail', compact('pengeluaran'));
    }

    /**
     * (OPSIONAL) DETAIL BERDASARKAN NOMOR
     * Kalau suatu saat dibutuhkan
     */
    public function detailLaporan($nomor_pengeluaran)
    {
        $pengeluaran = PengeluaranBarang::with('items')
            ->where('nomor_pengeluaran', $nomor_pengeluaran)
            ->firstOrFail();

        return view('pengeluaran-barang.detail', compact('pengeluaran'));
    }

    public function print($id)
{
    $pengeluaran = PengeluaranBarang::with('items.product')
        ->findOrFail($id);

    return view('pengeluaran-barang.print', compact('pengeluaran'));
}
}
