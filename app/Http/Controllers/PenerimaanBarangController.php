<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\PenerimaanBarang;
use App\Models\ItemPenerimaanBarang;
use App\Models\Product;

class PenerimaanBarangController extends Controller
{
    public function index()
    {
        return view('penerimaan-barang.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'distributor'  => 'required',
            'nomor_faktur' => 'required',
            'produk'       => 'required|array|min:1',
        ], [
            'distributor.required'  => 'Distributor Harus Diisi',
            'nomor_faktur.required' => 'Nomor Faktur Harus Diisi',
            'produk.required'       => 'Produk Harus Diisi',
        ]);

        DB::transaction(function () use ($request) {

            // =====================
            // HEADER PENERIMAAN
            // =====================
            $penerimaan = PenerimaanBarang::create([
                'nomor_penerimaan' => PenerimaanBarang::nomorPenerimaan(),
                'distributor'      => $request->distributor,
                'nomor_faktur'     => $request->nomor_faktur,
                'petugas_penerima' => Auth::user()->name,
            ]);

            // =====================
            // DETAIL PRODUK
            // =====================
            foreach ($request->produk as $item) {

                // 🔥 AMBIL PRODUK DARI DB (BUKAN DARI REQUEST)
                $produk = Product::findOrFail($item['produk_id']);

                ItemPenerimaanBarang::create([
                    'nomor_penerimaan' => $penerimaan->nomor_penerimaan,
                    'produk_id'        => $produk->id,
                    'nama_produk'      => $produk->nama_produk, // ✅ FIX UTAMA
                    'qty'              => $item['qty'],
                    'harga_beli'       => $item['harga_beli'],
                    'sub_total'        => $item['sub_total'],
                ]);

                // UPDATE STOK
                $produk->increment('stok', $item['qty']);
            }
        });

        toast()->success('Barang berhasil ditambahkan');

        // balik sesuai role
        return auth()->user()->role === 'kasir'
            ? redirect()->route('barang-datang')
            : redirect()->route('penerimaan-barang.index');
    }

    public function laporan()
    {
        $penerimaanBarang = PenerimaanBarang::orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->tanggal_penerimaan = Carbon::parse($item->created_at)
                    ->locale('id')
                    ->translatedFormat('l, d F Y');
                return $item;
            });

        return view('laporan.penerimaan-barang.laporan', compact('penerimaanBarang'));
    }

    public function detailLaporan(string $nomorPenerimaan)
    {
        $data = PenerimaanBarang::with('items')
            ->where('nomor_penerimaan', $nomorPenerimaan)
            ->firstOrFail();

        $data->tanggal_penerimaan = Carbon::parse($data->created_at)
            ->locale('id')
            ->translatedFormat('l, d F Y');

        $data->total = $data->items->sum('sub_total');

        return view('laporan.penerimaan-barang.detail', compact('data'));
    }
}
