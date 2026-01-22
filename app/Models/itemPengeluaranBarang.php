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
        'sub_total',
    ];
}
    