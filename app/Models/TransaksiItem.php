<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiItem extends Model
{
    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'qty',
        'harga',
        'sub_total'
    ];

    public function produk()
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
