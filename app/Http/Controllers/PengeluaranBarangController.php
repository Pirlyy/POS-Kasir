<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranBarang;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class PengeluaranBarangController extends Controller
{
    public function index()
    {
        return view('pengeluaran-barang.index');
    }

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

    // 🔥 INI YANG KURANG
    public function store(Request $request)
    {
        if (empty($request->produk)){
            toast()->error('Data produk belum ditambahkan');
            return redirect()->back();
        }
        request()->validate([
            'produk' => 'required|array|min:1',
            'bayar' => 'required|numeric|min:1',
        ] ,['produk.required' => 'Produk harus diisi',
          'produk.min' => 'Minimal 1 produk harus ditambahkan',
          'bayar.required' => 'Jumlah bayar harus diisi',
          'bayar.numeric' => 'Jumlah bayar harus berupa angka',
          'bayar.min' => 'Jumlah bayar minimal 1'
        ]
        );
        $produk = collect($request->produk);
        $bayar = $request->bayar;
        $total = $produk->sum('sub_total');
        $kembalian = intval($bayar) - intval($total);
        if ($bayar<$total){
            toast()->error('Jumlah bayar tidak mencukupi');
            return redirect()->back()->withInput([
                'produk' => $produk,
                'bayar' => $bayar ,
                'total' => $total,
                'kembalian' => $kembalian,
            ]);
            
        }
        $data =PengeluaranBarang::create([
            'nomor_pengeluaran' => PengeluaranBarang::nomerpengeluaran(),
            'nama_petugas' => auth::user()->name,
            'bayar' => $bayar,
            'kembalian' => $kembalian,
            'total_harga' => $total,
        ]); 
        dd($bayar, $total, $kembalian, );
    }
}
