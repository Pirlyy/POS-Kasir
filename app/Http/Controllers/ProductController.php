<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('kategori')->get();
        confirmDelete('Hapus data produk ini?', 'Hapus', 'Batal');

        return view('product.index', compact('products'));
    }

    public function store(Request $request)
    {
        $id = $request->id;

        $request->validate([
            'nama_produk' => 'required|unique:products,nama_produk,' . $id,
            'harga_jual' => 'required|numeric|min:0',
            'harga_beli_pokok' => 'required|numeric|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok' => 'required|numeric|min:0',
            'stok_minimal' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama_produk.required' => 'Nama produk harus diisi!',
            'nama_produk.unique' => 'Nama produk sudah ada!',
            'harga_jual.required' => 'Harga jual harus diisi!',
            'harga_jual.numeric' => 'Harga jual harus berupa angka!',
            'harga_beli_pokok.required' => 'Harga beli pokok harus diisi!',
            'harga_beli_pokok.numeric' => 'Harga beli pokok harus berupa angka!',
            'kategori_id.required' => 'Kategori harus diisi!',
            'kategori_id.exists' => 'Kategori tidak valid!',
            'stok.required' => 'Stok harus diisi!',
            'stok.numeric' => 'Stok harus berupa angka!',
            'image.image' => 'File harus berupa gambar!',
        ]);

        $data = [
            'nama_produk' => $request->nama_produk,
            'harga_jual' => $request->harga_jual,
            'harga_beli_pokok' => $request->harga_beli_pokok,
            'kategori_id' => $request->kategori_id,
            'stok' => $request->stok,
            'stok_minimal' => $request->stok_minimal,
            'is_active' => $request->is_active ? true : false,
        ];

        // Generate SKU jika create
        if (!$id) {
            $data['sku'] = Product::nomorSku();
        }

        // ================= IMAGE LOGIC =================
        if ($request->hasFile('image')) {

            // jika update → hapus image lama
            if ($id) {
                $oldProduct = Product::find($id);
                if (
                    $oldProduct &&
                    $oldProduct->image &&
                    Storage::disk('public')->exists($oldProduct->image)
                ) {
                    Storage::disk('public')->delete($oldProduct->image);
                }
            }

            $data['image'] = $request->file('image')->store('products', 'public');
        }
        // =================================================

        Product::updateOrCreate(
            ['id' => $id],
            $data
        );

        toast()->success('Data Produk Berhasil Disimpan!');
        return redirect()->route('master-data.product.index');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // hapus image
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        toast()->success('Data Produk Berhasil Dihapus!');
        return redirect()->route('master-data.product.index');
    }

    public function getData()
    {
        $search = request()->query('search');

        $products = Product::where('nama_produk', 'like', '%' . $search . '%')->get();

        return response()->json($products);
    }

    public function cekStok()
    {
        $id = request()->query('id');
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'stok' => 0,
                'harga_jual' => 0,
            ]);
        }

        return response()->json([
            'stok' => $product->stok,
            'harga_jual' => $product->harga_jual,
        ]);
    }
}
