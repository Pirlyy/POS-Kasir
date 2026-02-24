<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPengeluaranBarang extends Model
{
    protected $fillable = [
    'pengeluaran_barang_id',
    'product_id',
    'jumlah',
    'harga_jual',
    'diskon_persen', // ✅ TAMBAHKAN INI
    'sub_total',
];

    // ✅ INI YANG KURANG
public function product()
{
return $this->belongsTo(Product::class, 'product_id');
}
}
