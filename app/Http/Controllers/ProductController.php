<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('kategori')->get();
        confirmDelete('Hapus data kategori ini?', 'Hapus', 'Batal');
        return view('product.index', compact('products'));
    }

    public function store(Request $request){
        $id = $request->id;
        $request->validate([
            'nama_produk' => 'required|unique:products,nama_produk,' . $id,
            'harga_jual' => 'required|numeric|min:0',
            'harga_beli_pokok' => 'required|numeric|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok' => 'required|numeric|min:0',
            'stok_minimal' => 'required|numeric|min:0',
        ],[
            'nama_produk.required' => "nama produk harus diisi!",
            'nama_produk.unique' => "nama produk sudah ada",
            'harga_jual.required' => "harga jual harus diisi!",
            'harga_jual.numeric' => "Harga jual harus berupa angka!",
            'harga_jual.min' => "harga jual minimal 0!",
            'harga_beli_pokok.required' => "Harga beli pokok harus diisi!",
            'harga_beli_pokok.numeric' => "Harga beli pokok harus berupa angka!",
            'harga_beli_pokok.min' => "Harga beli pokok minimal 0!",
            'kategori_id.required' => "Kategori harus diisi!",
            'kategori_id.exists' => "Kategori tidak valid!",
            'stok.required' => "Stok harus diisi!",
            'stok.numeric' => "Stok harus berupa angka!",

        ]);

        $newRequest = [
            'id' => $id,
            'nama_produk' => $request->nama_produk,
            'harga_jual' => $request->harga_jual,
            'harga_beli_pokok' => $request->harga_beli_pokok,
            'kategori_id' => $request->kategori_id,
            'stok' => $request->stok,
            'stok_minimal' => $request->stok_minimal,
            'is_active' => $request->is_active ? true : false,
        ];

        if(!$id) {
            $newRequest['sku'] = Product::nomorSku();
        }
        Product::updateOrCreate(
            ["id" => $id],
            $newRequest
        );
        toast()->success('Data Berhasil Dibuat!');
        return redirect()->route('master-data.product.index');
    }

    public function destroy(String $id)
    {
        $product= Product::findOrFail($id);
        $product->delete();
        toast()->success("Data Berhasil dihapus");
        return redirect()->route('master-data.product.index');
    }

    public function getData(){
        $search = request()->query('search');

        $query = Product::query();

        $product = $query->where('nama_produk', 'like', '%' . $search . '%')->get();
        return response()->json($product);
    }

    public function cekStok(){
    $id = request()->query('id');

    $product = Product::find($id);

    if(!$product){
        return response()->json([
            'stok' => 0
        ]);
    }

    return response()->json([
        'stok' => $product->stok
    ]);
}

}
