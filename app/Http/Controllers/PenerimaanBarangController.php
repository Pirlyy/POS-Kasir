<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth; // ✅ INI WAJIB
use App\Models\PenerimaanBarang;
use App\Models\ItemPenerimaanBarang;
use App\Models\Product;

class PenerimaanBarangController extends Controller
{
    public function index(){
        return view('penerimaan-barang.index');
    }
    public function store(Request $request){
        $request->validate([
            'distributor'  => 'required',
            'nomor_faktur' => 'required',
            'produk'       => 'required',
        ],[
            'distributor.required' => 'Distributor Harus Diisi',
            'nomor_faktur.required' => 'Nomor Faktur Harus Diisi',
            'produk.required'       => 'Produk Harus Disi',
        ]);

            $newData = PenerimaanBarang::create([
                'nomor_penerimaan'  => PenerimaanBarang::nomorPenerimaan(),
                'distributor'       => $request->distributor,
                'nomor_faktur'      => $request->nomor_faktur,
                'petugas_penerima'  => Auth::user()->name,
            ]);

            $produk = $request->produk;
            foreach ($produk as $item){
                ItemPenerimaanBarang::create([
                    'nomor_penerimaan' => $newData->nomor_penerimaan,
                    'nama_produk'      => $item['nama_produk'],
                    'qty'              => $item['qty'],
                    'harga_beli'       => $item['harga_beli'],
                    'sub_total'        => $item['sub_total'],
                ]);

                Product::where('id', $item['produk_id'])->increment('stok', $item['qty']);
            }
            toast()->success('Data Berhasil Ditambahkan');
            return redirect()->route('penerimaan-barang.index');
    }
}
